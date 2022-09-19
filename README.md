 ## Laravel Paytr

**How to install?**

    composer require gizemsever/laravel-paytr
    
Publish provider
   
    php artisan vendor:publish --provider="Gizemsever\LaravelPaytr\PaytrServiceProvider"

Setup your environment

    PAYTR_MERCHANT_ID=''  
    PAYTR_MERCHANT_SALT=''  
    PAYTR_MERCHANT_KEY=''  
    PAYTR_SUCCESS_URL=''  
    PAYTR_FAIL_URL=''  
    PAYTR_TEST_MODE=true
