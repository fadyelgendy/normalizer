<?php

require_once(__DIR__ . "/Base.php");
require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/Session.php");

class Normalizer extends Base
{
    /**
     * Handle Normalization process
     *
     * @return void
     */
    public function handle(): void
    {
        $db = new Database();
        $data = $db->select("offers", ["*"]);

        $processed_data = [];
        foreach ($data as $row) {
            $title = $row['title'];
            $description = $row['description'];

            $temp = [];
            $temp["id"] = $row['id'];

            // title
            if (!empty($title)) {
                $temp['title'] =  $this->processString(preg_replace("/\t\n|\r|\n/", "", $title));
            }

            // description
            if (!empty($description)) {
                $temp['description'] =  $this->processString(preg_replace("/\t\n|\r|\n/", "", $description));
            }

            $processed_data[] = $temp;
        }

        // update
        foreach ($processed_data as $row) {
            $db->update("offers", $row);
        }

        $session = new Session("success", "data processed successfully!");
        header("Location: ../index.php");
    }

    /**
     * Handle Normalize form data
     *
     * @param array $data
     * @return void
     */
    public function handleFormData(array $data): void
    {
        $db = new Database();
        $processed_data = [];

        // title
        if (!empty($data["title"])) {
            $title = $this->processString(preg_replace("/\t\n|\r|\n/", "", $data["title"]));
            $processed_data['title'] =  is_null($title) ? $data['title'] : $title;
        }

        // description
        if (!empty($data["description"])) {
            $description = $this->processString(preg_replace("/\t\n|\r|\n/", "", $data["description"]));
            $processed_data['description'] = is_null($description) ? $data['description'] : $description;
        }

        // Insert
        $db->insert("offers", $processed_data);

        $session = new Session("success", "data processed successfully!");
        header("Location: ../index.php");
    }

    /**
     * Process a given string with a give pattern to find a match
     *
     * @param string $source
     * @param string $pattern
     * @return string|null
     */
    protected function processString(string $source): ?string
    {
        $source_to_array = explode(" ", $source);
        $pattern = $this->getRepteatedPattern($source_to_array[0]);

        if ($pattern) {
            return str_ireplace($pattern, "", $source);
        }
        return null;
    }

    /**
     * Get repated pattern [word] from string
     *
     * @param string $source
     * @return string|null
     */
    protected function getRepteatedPattern(string $source): ?string
    {
        $word_indxs = [];
        $freq = [];

        // because the repeated words is city name, and
        // starts with capital letters
        // so, here we get each capital letter index
        for ($i = 0; $i < strlen($source); $i++) {
            if (preg_match("/[A-Z]+/", $source[$i]))
                $word_indxs[] = $i;
        }

        if (!empty($word_indxs)) {

            // split, by each given indexs
            for ($i = 0; $i < count($word_indxs) - 1; $i++) {
                $freq[] = substr($source, $word_indxs[$i], $word_indxs[$i + 1] - $word_indxs[$i]);
            }

            return array_key_exists(0, $freq) ? $freq[0] : null;
        }

        return null;
    }
}
