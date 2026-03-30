<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\Setting;
use SimpleXMLElement;

class CoretaxXmlGenerator
{
    /**
     * Generate Coretax XML for a given period
     */
    public function generate(int $month, int $year): string
    {
        $payrolls = Payroll::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $companyNpwp = Setting::getValue('company_npwp', '000000000000000');
        $companyNitku = Setting::getValue('company_nitku', '000000');

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><MmPayrollBulk></MmPayrollBulk>');

        $xml->addChild('TIN', $companyNpwp);

        $listOfPayroll = $xml->addChild('ListOfMmPayroll');

        foreach ($payrolls as $payroll) {
            $employee = $payroll->employee;

            $mmPayroll = $listOfPayroll->addChild('MmPayroll');
            $mmPayroll->addChild('TaxPeriodMonth', str_pad($month, 2, '0', STR_PAD_LEFT));
            $mmPayroll->addChild('TaxPeriodYear', (string) $year);
            $mmPayroll->addChild('CounterpartOpt', 'Resident');
            $mmPayroll->addChild('CounterpartTin', preg_replace('/[^0-9]/', '', $employee->npwp));
            $mmPayroll->addChild('StatusTaxExemption', $employee->ptkp_status);
            $mmPayroll->addChild('Position', $employee->position ?? '-');
            $mmPayroll->addChild('TaxCertificate', 'N/A');
            $mmPayroll->addChild('TaxObjectCode', '21-100-01');
            $mmPayroll->addChild('Gross', number_format($payroll->total_income, 0, '.', ''));
            $mmPayroll->addChild('PPh', number_format(floor($payroll->pph21), 0, '.', ''));

            // Calculate effective rate
            $rate = $payroll->total_income > 0
                ? round(($payroll->pph21 / $payroll->total_income) * 100, 2)
                : 0;
            $mmPayroll->addChild('Rate', number_format($rate, 2, '.', ''));

            $mmPayroll->addChild('IDPlaceOfBusinessActivity', $companyNitku);

            // Withholding date = last day of the period month
            $lastDay = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
            $mmPayroll->addChild('WithholdingDate', $lastDay);
        }

        // Format the XML nicely
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return $dom->saveXML();
    }

    /**
     * Get the count of payroll records for a period
     */
    public function getRecordCount(int $month, int $year): int
    {
        return Payroll::where('month', $month)
            ->where('year', $year)
            ->count();
    }
}
