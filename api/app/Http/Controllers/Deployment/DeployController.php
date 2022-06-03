<?php

namespace App\Http\Controllers\Deployment;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDeploy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DeployController extends Controller
{

    public function deploy(Request $request)
    {
//        $githubPayload = $request->getContent();
//        $githubHash = $request->header('X-Hub-Signature');
//        $localToken = config('app.deploy_secret');
//        $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);
//        if (1 == 1) {
        ProcessDeploy::dispatch();


        return 'ok';
//        }
    }
}
