<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-10
 * Time: 15:33
 */

namespace App\SmartResource;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SmartResourceController extends Controller {
    
    public function __invoke(Request $request) {
        $token = $request->get( 'token');
        $binder = $request->get( 'binder');
        $method = $request->get( 'method');
        $arguments = json_decode( $request->get( 'arguments', '[]'), true);
        try{
            $binder = app($binder);
            $result = call_user_func_array( [$binder, $method], $arguments);
            return response()->json([
                'success' => true,
                'data' => serialize( $result ),
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage(),
                'data' => null,
            ]);
        }
    }
    
}