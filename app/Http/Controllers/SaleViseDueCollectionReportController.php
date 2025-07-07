<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;
use Carbon\Carbon;
use DB;

class SaleViseDueCollectionReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with([
            'project.company',
            'unit',
            'customer',
            'saleBy',
            'handlingPerson'
        ])
        ->whereNotIn('active_status', [1, 2]);

        // Filter by Active/Inactive logic
      

        // Filters
        if ($request->startDate && $request->endDate) {
            $query->whereBetween('sale_date', [$request->startDate, $request->endDate]);
        } elseif ($request->startDate) {
            $query->whereDate('sale_date', $request->startDate);
        } elseif ($request->endDate) {
            $query->whereDate('sale_date', $request->endDate);
        }

        if ($request->selectedProject && $request->selectedProject !== 'SelectProject') {
            $query->where('project_id', $request->selectedProject);
        }

        if ($request->selectedCompany && $request->selectedCompany !== 'SelectCompany') {
            $query->whereHas('project.company', function ($q) use ($request) {
                $q->where('company_id', $request->selectedCompany);
            });
        }

        if ($request->selectedSalesMember && $request->selectedSalesMember !== 'SelectSalesMember') {
            $query->where('sale_by', $request->selectedSalesMember);
        }

        if ($request->selectedHandlingPerson) {
            $query->where('handling_person', $request->selectedHandlingPerson);
        }

        $sales = $query->get();

        $data = $sales->map(function ($sale) {
            $paidAmount = Payment::where('sale_id', $sale->sale_id)
                ->where('active_status', 0)
                ->sum('paid_amount');

            // Calculate due amount only for overdue installments that are not fully paid
            $dueAmount = DB::table('payment_plan as pp')
                ->where('pp.sale_id', $sale->sale_id)
                ->whereRaw("COALESCE(pp.actual_due_date, pp.due_date) < CURDATE()")
                ->where(function ($query) {
                    // Only consider installments where there is still an outstanding amount
                    $query->whereRaw("
                        (pp.amount - COALESCE(
                            (SELECT SUM(paid_amount) FROM payments
                             WHERE sale_id = pp.sale_id AND pay_discription = pp.installment AND active_status = 0), 0)
                        ) > 0
                    ");
                })
                ->selectRaw("SUM(
                    pp.amount - COALESCE(
                        (SELECT SUM(paid_amount) FROM payments
                         WHERE sale_id = pp.sale_id AND pay_discription = pp.installment AND active_status = 0), 0)
                ) as due_amount")
                ->value('due_amount');

            return [
                'sale_date' => $sale->sale_date,
                'sale_id' => $sale->sale_id,
                'project_id' => $sale->project_id,
                'project_name' => $sale->project->project_name ?? '',
                'unit_id' => $sale->unit_id,
                'unit_Name' => $sale->unit->unit_Name ?? '',
                'company_name' => $sale->project->company->company_name ?? '',
                'Customer_id' => $sale->Customer_id,
                'C_namewinitials' => $sale->customer->C_namewinitials ?? '',
                'handlingPersonFName' => $sale->handlingPerson->U_FName ?? '',
                'handlingPersonLName' => $sale->handlingPerson->U_LName ?? '',
                'active_status' => $sale->active_status,
                'selling_price' => $sale->selling_price,
                'paid_amount' => $paidAmount,
                'balance' => $sale->selling_price - $paidAmount,
                'total_due_amount' => $dueAmount ?? 0,
            ];
        });

        return response()->json($data);
    }
}

