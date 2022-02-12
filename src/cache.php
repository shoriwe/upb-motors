<?php


class Cache
{
    public ?string $filename = null;
    public $file_handle = null;

    public function __construct(string $filename)
    {
        $filename = sys_get_temp_dir() . "/" . $filename;
        if (!file_exists($filename)) {
            touch($filename);
        }
        $this->filename = $filename;
        $this->file_handle = fopen($filename, "r+");
    }

    public function __destruct()
    {
        fclose($this->file_handle);
    }

    public function delete(int|string $key)
    {
        flock($this->file_handle, LOCK_EX);
        $o = $this->load_file();
        if (array_key_exists($key, $o)) {
            unset($o[$key]);
        }
        $this->write_to_file($o);
        flock($this->file_handle, LOCK_UN);
    }

    public function load_file(): ?array
    {
        if (filesize($this->filename) === 0) {
            return array();
        }
        fseek($this->file_handle, 0);
        $contents = fread($this->file_handle, filesize($this->filename));
        fseek($this->file_handle, 0);
        if ($contents) {
            return unserialize($contents);
        }
        return null;
    }

    public function write_to_file(array $contents)
    {
        fseek($this->file_handle, 0);
        fwrite($this->file_handle, serialize($contents));
        fseek($this->file_handle, 0);
    }

    public function get(int|string $key)
    {
        flock($this->file_handle, LOCK_EX);
        $o = $this->load_file();
        flock($this->file_handle, LOCK_UN);
        if (array_key_exists($key, $o)) {
            return $o[$key];
        }
        return null;
    }

    public function set(int|string $key, $value)
    {
        flock($this->file_handle, LOCK_EX);
        $o = $this->load_file();
        $o[$key] = $value;
        $this->write_to_file($o);
        flock($this->file_handle, LOCK_UN);
    }
}