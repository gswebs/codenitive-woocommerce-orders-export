<?php
namespace WooJMBExporter;

defined('ABSPATH') || exit;

abstract class BaseExporter {

    protected $data = [];

    abstract protected function fetch_data(): void;

    protected function output_csv(array $header, array $rows, string $filename = 'export.csv'): void {
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment;filename={$filename}");
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fputcsv($output, $header);

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    public function export(): void {
        $this->fetch_data();
    
        $headers = [];
    
        foreach ($this->filters['selected_fields'] as $field) {
            $headers[] = $field;
        }
    
        $headers = apply_filters('jmb_export_order_data_header', $headers);
    
        $this->output_csv($headers, $this->data, 'orders-export.csv');
    }

    
}
