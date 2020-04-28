<?php

namespace App\Http\Response;

trait jsonResponse
{
    public function jsonSuccessResponse($data=null,$message="成功"){
        return $this->jsonResponse(200,$message,$data);
    }

    public function jsonErrorResponse($code=401,$message="失败",$body=null){
        return $this->jsonResponse($code,$message,$body);
    }

    private function jsonResponse($code,$message,$body)
    {
        return response()->json([
            'code'=>$code,
            'message'=>$message,
            'body'=>$body
        ],$code);
    }
}
