<?php

namespace api\admin_reports;

use services\ReportsService;

class ReportsApiController
{
    public function getData()
    {
        $reportsService = new ReportsService();

        $activeUsers =
            $reportsService->getActiveUsers();

        $sessionsPerDay =
            $reportsService->getSessionsPerDay();

        $topTrainers =
            $reportsService->getTopTrainers();

        $subscriptionStats =
            $reportsService->getSubscriptionStats();


        http_response_code(200);

        echo json_encode([

            'activeUsers' => $reportsService->getActiveUsers(),
            'todaySessions' => $reportsService->getTodaySessionsCount(),
            'weekSessions' => $reportsService->getThisWeekSessionsCount(),
            'monthSessions' => $reportsService->getThisMonthSessionsCount(),
            'sessionsPerDay' => $reportsService->getSessionsPerDay(),
            'topTrainers' => $reportsService->getTopTrainers(),
            'subscriptionStats' => $reportsService->getSubscriptionStats()
        ]);
    }

    public function export()
    {
        $data = json_decode(
            file_get_contents(
                'php://input'
            ),
            true
        );

        $report =
            $data['report'] ?? '';

        $format =
            $data['format'] ?? '';

        $reportsService =
            new ReportsService();

        try{

            $reportData =
                $reportsService
                    ->getReportData(
                        $report
                    );

            if($format === 'csv'){

                $reportsService
                    ->exportCsv(
                        $reportData,
                        $report
                    );
            }

            if($format === 'xml'){

                $reportsService
                    ->exportXml(
                        $reportData,
                        $report
                    );
            }

        }catch(\Exception $ex){

            http_response_code(400);

            echo json_encode([
                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }
}