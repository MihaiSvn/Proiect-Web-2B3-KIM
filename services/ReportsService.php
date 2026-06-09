<?php

namespace services;

use models\User;
use models\Booking;
use models\UserSubscription;

class ReportsService
{
    public function getActiveUsers()
    {
        return User::getActiveUsersCount();
    }

    public function getSessionsPerDay()
    {
        return Booking::getSessionsPerDay();
    }

    public function getSessionsPerWeek()
    {
        return Booking::getSessionsPerWeek();
    }

    public function getSessionsPerMonth()
    {
        return Booking::getSessionsPerMonth();
    }

    public function getTopTrainers()
    {
        return Booking::getTopTrainers();
    }

    public function getSubscriptionStats()
    {
        return UserSubscription::getSubscriptionTypeStats();
    }

    public function getTodaySessionsCount()
    {
        return Booking::getTodaySessionsCount();
    }

    public function getThisWeekSessionsCount()
    {
        return Booking::getThisWeekSessionsCount();
    }

    public function getThisMonthSessionsCount()
    {
        return Booking::getThisMonthSessionsCount();
    }

    public function getReportData($report)
    {
        switch($report){

            case 'sessions':
                return $this->getSessionsPerDay();

            case 'trainers':
                return $this->getTopTrainers();

            case 'distribution':
                return $this->getSubscriptionStats();

            default:
                throw new \Exception(
                    'Invalid report'
                );
        }
    }

    public function exportCsv(
        $data,
        $fileName
    )
    {
        header(
            'Content-Type: text/csv'
        );

        header(
            "Content-Disposition: attachment; filename=\"$fileName.csv\""
        );

        $output =
            fopen(
                'php://output',
                'w'
            );

        if(empty($data)){
            exit;
        }

        fputcsv(
            $output,
            array_keys(
                (array)$data[0]
            ),
            ';'
        );

        foreach($data as $row){

            $rowArray =
                array_map(
                    fn($value) => '="' . $value . '"',
                    (array)$row
                );

            fputcsv(
                $output,
                $rowArray,
                ';'
            );
        }

        fclose($output);

        exit;
    }
    public function exportXml(
        $data,
        $rootName
    )
    {
        $xml =
            new \SimpleXMLElement(
                "<$rootName/>"
            );

        foreach($data as $row){

            $item =
                $xml->addChild(
                    'item'
                );

            foreach(
                (array)$row as $key => $value
            ){

                $item->addChild(
                    $key,
                    htmlspecialchars(
                        (string)$value
                    )
                );
            }
        }

        header(
            'Content-Type: application/xml'
        );

        header(
            "Content-Disposition: attachment; filename=\"$rootName.xml\""
        );

        echo $xml->asXML();

        exit;
    }
}