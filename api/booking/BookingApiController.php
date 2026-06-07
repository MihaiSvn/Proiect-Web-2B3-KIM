<?php

namespace api\booking;

use services\BookingService;

class BookingApiController
{
    public function book(){
        $data = json_decode(file_get_contents('php://input'), true);
        $sessionId = isset($data['session_id']) ? $data['session_id'] : null;

        $userId = $_SESSION['user_id'];


        if(!$sessionId){
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Session ID is missing']);
            exit;
        }

        $bookingService = new BookingService();
        try{
            $bookingService->createBooking($userId, $sessionId);
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Session booked successfully.']);
            exit;
        } catch (\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }
    public function cancel(){
        $data = json_decode(file_get_contents('php://input'),true);

        $sessionId = isset($data['session_id']) ? $data['session_id'] : null;

        $userId = $_SESSION['user_id'];

        if(!$sessionId){
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Session ID is missing']);
            exit;
        }

        $bookingService = new BookingService();
        try{
            $bookingService->cancelUserBooking($userId, $sessionId);

            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Your booking was successfully canceled.']);
            exit;
        } catch(\Exception $e){
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}