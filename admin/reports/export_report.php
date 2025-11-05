<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Include database connection
require_once '../db_connection.php';

$link = get_db_connection();

// Get parameters
$report_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$format = isset($_GET['format']) ? $_GET['format'] : 'csv';

if (!$report_id) {
    die('Invalid report ID');
}

// Get report configuration
$stmt = mysqli_prepare($link, "SELECT * FROM reports WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $report_id);
mysqli_stmt_execute($stmt);
$report_result = mysqli_stmt_get_result($stmt);
$report = mysqli_fetch_assoc($report_result);

if (!$report) {
    die('Report not found');
}

// Get report data
$stmt_data = mysqli_prepare($link, "SELECT data FROM report_data WHERE report_id = ? ORDER BY generated_date DESC LIMIT 1");
mysqli_stmt_bind_param($stmt_data, "i", $report_id);
mysqli_stmt_execute($stmt_data);
$data_result = mysqli_stmt_get_result($stmt_data);
$data_row = mysqli_fetch_assoc($data_result);

if (!$data_row) {
    die('Report data not found');
}

$report_data = json_decode($data_row['data'], true);

mysqli_close($link);

// Export based on format
if ($format === 'csv') {
    exportToCSV($report_data, $report);
} elseif ($format === 'pdf') {
    exportToPDF($report_data, $report);
} else {
    die('Invalid export format');
}

function exportToCSV($data, $report) {
    if (empty($data)) {
        die('No data to export');
    }

    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $report['name']) . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    // Get headers based on report type
    $headers = getHeadersForReportType($report['type']);
    fputcsv($output, $headers);

    // Output data rows
    foreach ($data as $row) {
        $csvRow = getCSVRowForReportType($row, $report['type']);
        fputcsv($output, $csvRow);
    }

    fclose($output);
    exit;
}

function exportToPDF($data, $report) {
    // For PDF export, we'll create a simple HTML-based PDF
    // In a production environment, you'd use a proper PDF library like TCPDF, FPDF, or DomPDF

    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $report['name']) . '.pdf';

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Create simple HTML content that can be converted to PDF
    $html = generatePDFHTML($data, $report);

    // For now, we'll output as HTML that can be saved as PDF
    // In production, use a PDF library
    echo $html;
    exit;
}

function getHeadersForReportType($type) {
    switch ($type) {
        case 'contact':
            return ['ID', 'Name', 'Email', 'Company', 'Country', 'Job Title', 'Status', 'Priority', 'Submission Date'];
        case 'feedback':
            return ['ID', 'Name', 'Email', 'Company', 'Project', 'Rating', 'Status', 'Submission Date'];
        case 'articles':
            return ['ID', 'Title', 'Author', 'Category', 'Status', 'Date', 'Reading Time'];
        default:
            return ['ID', 'Data'];
    }
}

function getCSVRowForReportType($row, $type) {
    switch ($type) {
        case 'contact':
            return [
                $row['id'],
                $row['full_name'],
                $row['email'],
                $row['company'] ?: 'N/A',
                $row['country'] ?: 'N/A',
                $row['job_title'] ?: 'N/A',
                $row['status'],
                $row['priority'],
                $row['submission_date']
            ];
        case 'feedback':
            return [
                $row['id'],
                $row['full_name'],
                $row['email'],
                $row['company'] ?: 'N/A',
                $row['project'] ?: 'N/A',
                $row['rating'],
                $row['status'],
                $row['submission_date']
            ];
        case 'articles':
            return [
                $row['id'],
                $row['title'],
                $row['author'],
                $row['category'],
                $row['status'],
                $row['date'],
                $row['reading_time'] ?: 'N/A'
            ];
        default:
            return [$row['id'], json_encode($row)];
    }
}

function generatePDFHTML($data, $report) {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>' . htmlspecialchars($report['name']) . '</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
            .report-info { margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 5px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #007bff; color: white; }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .no-data { text-align: center; padding: 50px; color: #666; }
        </style>
    </head>
    <body>
        <h1>' . htmlspecialchars($report['name']) . '</h1>

        <div class="report-info">
            <strong>Report Type:</strong> ' . ucfirst($report['type']) . '<br>
            <strong>Generated:</strong> ' . date('F j, Y \a\t g:i A') . '<br>
            <strong>Date Range:</strong> ' . date('M j, Y', strtotime($report['date_range_start'])) . ' to ' . date('M j, Y', strtotime($report['date_range_end'])) . '<br>
            <strong>Total Records:</strong> ' . count($data) . '<br>';

    if ($report['description']) {
        $html .= '<strong>Description:</strong> ' . htmlspecialchars($report['description']) . '<br>';
    }

    $html .= '</div>';

    if (empty($data)) {
        $html .= '<div class="no-data">No data found for this report.</div>';
    } else {
        $html .= '<table>';
        $html .= '<thead><tr>';

        $headers = getHeadersForReportType($report['type']);
        foreach ($headers as $header) {
            $html .= '<th>' . $header . '</th>';
        }

        $html .= '</tr></thead><tbody>';

        foreach ($data as $row) {
            $html .= '<tr>';
            $csvRow = getCSVRowForReportType($row, $report['type']);
            foreach ($csvRow as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
    }

    $html .= '
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 12px;">
            Generated by AI-Solutions Admin Panel on ' . date('F j, Y \a\t g:i A') . '
        </div>
    </body>
    </html>';

    return $html;
}
?>
