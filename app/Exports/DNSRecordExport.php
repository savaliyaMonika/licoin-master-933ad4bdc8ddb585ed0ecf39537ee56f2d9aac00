<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DNSRecordExport implements FromArray, WithHeadings
{
	protected $DnsRecord;

    public function __construct(array $DnsRecord)
    {
        $this->DnsRecord = $DnsRecord;
    }

    public function array(): array
    {
        return $this->DnsRecord;
    }	

    public function headings(): array
    {
        return [
            'Name',
            'Record'
        ];
    }
}
