<?php


namespace Gallery\Plugin;


class Information
{
    public $name;
    public $class;
    public $version;
    public $author;
    public $authorUrl;
    public $title;
    public $titleI18n;
    public $description;
    public $descriptionI18n;
    public $status = 0;
    /**
     * @var callable $closure
     */
    public $closure;

    public function __construct($data) {
        if (!empty($data['name'])) {
            $this->name = $data['name'];
        }
        if (!empty($data['class'])) {
            $this->class = $data['class'];
        }
        if (!empty($data['version'])) {
            $this->version = $data['version'];
        }
        if (!empty($data['author'])) {
            $this->author = $data['author'];
        }
        if (!empty($data['authorUrl'])) {
            $this->authorUrl = $data['authorUrl'];
        }
        if (!empty($data['title'])) {
            $this->title = $data['title'];
        }
        if (!empty($data['titleI18n'])) {
            $this->titleI18n = $data['titleI18n'];
        }
        if (!empty($data['description'])) {
            $this->description = $data['description'];
        }
        if (!empty($data['descriptionI18n'])) {
            $this->descriptionI18n = $data['descriptionI18n'];
        }
        if (!empty($data['closure'])) {
            $this->closure = $data['closure'];
        }
        if (!empty($data['status'])) {
            $this->status = $data['status'];
        }
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'author' => $this->author,
            'authorUrl' => $this->authorUrl,
            'title' => $this->title,
            'titleI18n' => $this->titleI18n,
            'description' => $this->description,
            'descriptionI18n' => $this->descriptionI18n,
            'closure' => $this->closure,
            'status' => $this->status,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toString()
    {
        return json_encode($this->toArray());
    }
}
