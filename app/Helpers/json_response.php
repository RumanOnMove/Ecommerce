<?php
/*
* Author: Rs Ruman
* Email: rsbdruman@gmail.com
*/

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

if (!function_exists('json_response')){
    function json_response($statusText='Success', $status=200, $data=null, $message="", $ok=true)
    {
        return response()->json([
            'status'=>$status,
            'statusText'=>ucfirst($statusText),
            'message'=>$message,
            'data'=>$data,
            'ok'=>$ok,
        ]);
    }
}

if(!function_exists('build_collection_response')){
    function build_collection_response(Request $request, $list)
    {
        if (!empty($request->per_page)) {
            $list = $list->paginate($request->per_page);
        }elseif (!empty($request->page)){
            $list = $list->paginate();
        }elseif(!empty($request->take)){
            $list = $list->take($request->take)->get();
        }else{
            $list = $list->get();
        }
        return $list;
    }
}

if(!function_exists('collection_response')){
    function collection_response($collection, $statusText='Success', $status=200, $message=null){
        return $collection->additional(
            [
                'statusText'=>ucfirst($statusText),
                'status'=>$status,
                'ok'=>true,
                'message'=>$message
            ]);
    }
}

if (!function_exists('validation_response')){
    function validation_response($errors=[])
    {
        $message = null;
        foreach ($errors as $error){
            if(!empty($error)){
                foreach ($error as $errorItem){
                    $message .=  $errorItem.',';
                }
            }
        }
        return response()->json([
            'status'=> ResponseAlias::HTTP_NOT_ACCEPTABLE,
            'statusText'=>'Validation',
            'ok'=>false,
            'message'=>$message,
        ]);
    }
}
