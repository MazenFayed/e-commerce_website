<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Exceptions\TwilioException;
use App\Providers\RouteServiceProvider;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twilio\Exceptions\ConfigurationException;

class VerifyMobileController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            if ($request->code == auth()->user()->mobile_verify_code) { {
                    $request->user()->markMobileAsVerified();
                    return redirect()->to(RouteServiceProvider::HOME)->with(['message' => __('mobile.verified')]);
                }
            }
            return back()->withErrors(['error' => __('mobile.error_code')]);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function send_sms()
    {
        try {
            $basic  = new \Vonage\Client\Credentials\Basic("e8e8450b", "xKBCo2h1kTofGpzx");
            $client = new \Vonage\Client($basic);
            $code = Auth()->user()->mobile_verify_code;
            $phone = Auth()->user()->phone;
            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS($phone, 'vshop', "Vshop Verification Code is : $code")
            );
            $message = $response->current();
            if ($message->getStatus() == 0) {
                echo "The message was sent successfully\n";
            } else {
                echo "The message failed with status: " . $message->getStatus() . "\n";
            }
            return Inertia::render('Auth/VerifyPhone');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
