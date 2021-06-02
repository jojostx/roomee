<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class Base64Image implements Rule
{


     /**
     * Array of supporting parameters.
     *
     **/
    protected array $parameters;

     /**
     * Pointer to the temporary file.
     *
     **/
    protected $file;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->parameters = func_get_args();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $valid_mime = false;

        foreach ($this->parameters as $mime) {
            if (Str::startsWith($value, "data:image/$mime;base64,")) {
                $valid_mime = true;

                break;
            }
        }

        if ($valid_mime) {
            $result = validator(['file' => $this->createTemporaryFile($value)], ['file' => 'image'])->passes();

            if (!empty($this->file)) fclose($this->file);

            return $result;
        }

        return false;
    }


    /**
     * Write the given data to a temporary file.
     *
     * @param string $data
     * @return UploadedFile
     */
    protected function createTemporaryFile(string $data)
    {        
        $this->file = tmpfile();

        fwrite($this->file,
            base64_decode(Str::after($data, 'base64,')));

        return new UploadedFile(stream_get_meta_data($this->file)['uri'], 'image',null, null, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $mimes = $this->parameters;

        if (count($mimes) === 1) {
            return $this->getLocalizedErrorMessage(
                'encoded_image',
                'The :attribute must be a valid ' . $mimes[0] . ' image'
            );
        }

        $mimes[count($mimes) - 1] = 'or ' . $mimes[count($mimes) - 1];

        return $this->getLocalizedErrorMessage(
            'encoded_image',
            'The :attribute must be a valid ' . implode(', ', $mimes) . ' image'
        );
    }

    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getLocalizedErrorMessage(string $key, string $default) : string
    {
        return trans("validation.$key") === "validation.$key" ? $default : trans("validation.$key");
    }

    // function is_base64($str){
    //     if($str === base64_encode(base64_decode($str))){
    //         return true;
    //     }
    //     return false;
    // }
}
