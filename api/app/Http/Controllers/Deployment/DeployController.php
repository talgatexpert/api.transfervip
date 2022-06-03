<?php

namespace App\Http\Controllers\Deployment;

use App\Http\Controllers\Controller;
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
            $root_path = base_path();
            $process = new Process([$root_path . '/deploy/deploy.sh']);
            $process->run(function ($type, $buffer) {
                echo $buffer;
            });
//            Log::write('UPDATE', 'deployment completed successfully');
            return 'ok';
//        }
    }
}
