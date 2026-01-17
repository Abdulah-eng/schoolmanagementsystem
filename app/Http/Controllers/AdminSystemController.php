<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class AdminSystemController extends Controller
{
    /**
     * Show system configuration
     */
    public function index()
    {
        $config = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'mail_driver' => config('mail.default'),
            'openai_enabled' => !empty(config('openai.api_key')),
        ];
        
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
        
        return view('admin.system.index', compact('config', 'systemInfo'));
    }
    
    /**
     * Update system configuration
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'openai_api_key' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
        ]);
        
        // Update .env file (this is a simplified approach)
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        
        $updates = [
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
            'OPENAI_API_KEY' => $request->openai_api_key,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
        ];
        
        foreach ($updates as $key => $value) {
            if ($value !== null) {
                $pattern = "/^{$key}=.*/m";
                $replacement = "{$key}={$value}";
                
                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    $envContent .= "\n{$replacement}";
                }
            }
        }
        
        file_put_contents($envPath, $envContent);
        
        // Clear config cache
        Artisan::call('config:clear');
        
        return back()->with('success', 'System configuration updated successfully');
    }
    
    /**
     * Clear system cache
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        return back()->with('success', 'System cache cleared successfully');
    }
    
    /**
     * Run database maintenance
     */
    public function runMaintenance()
    {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        
        return back()->with('success', 'Database maintenance completed successfully');
    }
    
    /**
     * Get system logs
     */
    public function getLogs(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return response()->json(['logs' => 'No log file found']);
        }
        
        $lines = $request->get('lines', 100);
        $logs = $this->tail($logFile, $lines);
        
        return response()->json(['logs' => $logs]);
    }
    
    /**
     * Tail function to read last N lines of a file
     */
    private function tail($file, $lines = 100)
    {
        $handle = fopen($file, "r");
        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = array();
        
        while ($linecounter > 0) {
            $t = " ";
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }
            $linecounter--;
            if ($beginning) {
                rewind($handle);
            }
            $text[$lines - $linecounter - 1] = fgets($handle);
            if ($beginning) break;
        }
        fclose($handle);
        return array_reverse($text);
    }
    
    /**
     * Get system health status
     */
    public function getHealth()
    {
        $health = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'openai' => $this->checkOpenAI(),
        ];
        
        $overall = !in_array(false, $health);
        
        return response()->json([
            'overall' => $overall,
            'checks' => $health,
        ]);
    }
    
    private function checkDatabase()
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function checkCache()
    {
        try {
            Cache::put('health_check', 'ok', 1);
            return Cache::get('health_check') === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function checkStorage()
    {
        return is_writable(storage_path()) && is_writable(storage_path('logs'));
    }
    
    private function checkOpenAI()
    {
        return !empty(config('openai.api_key'));
    }
}
