<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Settings;

use App\Entity\CmsSettings;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Api
{
    private string $token;
    private string $username;
    private string $password;
    private string $server;
    private string $dedipass_public;
    private string $dedipass_private;


    public function __construct(
        protected EntityManagerInterface $em,
        protected HttpClientInterface $client
    ) {
        $this->em = $em;
        $this->token = $em->getRepository(CmsSettings::class)->findOneBy(['setting' => 'api_token'])->getDefaultValue();
        $this->username = $em->getRepository(CmsSettings::class)->findOneBy(['setting' => 'api_username'])->getDefaultValue();
        $this->password = $em->getRepository(CmsSettings::class)->findOneBy(['setting' => 'api_password'])->getDefaultValue();
        $this->server = $em->getRepository(CmsSettings::class)->findOneBy(['setting' => 'api_server'])->getDefaultValue();
        $this->client = $client;
        $this->dedipass_public = $em->getRepository(CmsSettings::class)->findOneBy(['setting' => 'credit_dedipass_public_key'])->getDefaultValue();
        $this->dedipass_private = $em->getRepository(CmsSettings::class)->findOneBy(['setting' => 'credit_dedipass_private_key'])->getDefaultValue();
    }

    /**
     * Api Call POST
     *
     * @param string $server
     * @param array $postData
     * @param string $access_token
     * @param string $calltype
     * @return array<mixed>
     */
    public function APIcall_POST(string $server, array $postData, string $access_token, string $calltype): array
    {
        $ch = curl_init($server . $calltype);
        $sslVerifyPeer = strpos($server, "localhost") !== false ? false : true;

        if ($postData != null) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_SSL_VERIFYPEER => $sslVerifyPeer,
                CURLOPT_TIMEOUT_MS => 0,
                CURLOPT_HTTPHEADER => array(
                    'authorization:Bearer ' . $access_token, // "authorization:Bearer", et non pas "authorization: Bearer"
                    'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
            ));
        } else {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_SSL_VERIFYPEER => $sslVerifyPeer,
                CURLOPT_TIMEOUT_MS => 1000,
                CURLOPT_HTTPHEADER => array(
                    'authorization:Bearer ' . $access_token, // "authorization:Bearer", et non pas "authorization: Bearer"
                    'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                ),
            ));
        }

        $response = curl_exec($ch);

        if ($response === false) {
            $data = ['error' => true, 'message' => curl_error($ch)];
            return $data;
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode == 401 || $httpcode == 403) {
            $response = '{"Message": "Authorization has been denied for this request."}';
        }

        if($httpcode == 404) {
            $response = '{"Message": "Not Found"}';
        }

        $responseData = json_decode($response, true);
        curl_close($ch);

        return $responseData;
    }

    /**
     * Api Call GET
     *
     * @param string $server
     * @param string $access_token
     * @param string $calltype
     * @return array<mixed>
     */
    public function APIcall_GET(string $server, string $access_token, string $calltype): array
    {
        $ch = curl_init($server . $calltype);
        $sslVerifyPeer = strpos($server, "localhost") !== false ? false : true;

        curl_setopt_array($ch, array(
            CURLOPT_HTTPGET => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => $sslVerifyPeer,
            CURLOPT_TIMEOUT_MS => 1000,
            CURLOPT_HTTPHEADER => array(
                'authorization:Bearer ' . $access_token, // "authorization:Bearer", et non pas "authorization: Bearer"
                'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($http_status === 0) {
            $data = ['error' => true];
            return $data;
        }

        if ($response === false) {
            $data = ['error' => true, 'message' => curl_error($ch)];
            return $data;
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($httpcode == 401 || $httpcode == 403) {
            $response = '{"Message": "Authorization has been denied for this request."}';
        }

        if($httpcode == 404) {
            $response = '{"Message": "Not Found"}';
        }

        $responseData = json_decode($response, true);
        curl_close($ch);
        return $responseData;
    }

    /**
     * Retourne les données de l'API
     *
     * @return array<mixed>
     */
    public function getApiData(): array
    {
        // API login
        $postData = array(
            'grant_type' => "password",
            'username' => $this->getUsername(),
            'password' =>  $this->getPassword()
        );

        return $postData;
    }

    /**
     * Retourne le statut du serveur
     *
     * @return array<mixed>
     */
    public function ServeurStatut(): array
    {
        $server_infos = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/info/stats');

        if (isset($server_infos['Message']) && $server_infos['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server_infos = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/info/stats');
        }

        if (isset($server_infos['uptime'])) {
            return ['success' => true, 'online' => $server_infos['onlineCount']];
        } else {
            return  ['success' => false];
        }
    }

    /**
     * Permet de changer le mot de passe
     *
     * @param array $data
     * @param string $username
     * @return boolean
     */
    public function passwordVerify(array $data, string $username): bool
    {
        $apiPasswordVerify = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/users/' . $username . '/password/validate');

        if (isset($apiPasswordVerify['Message']) && $apiPasswordVerify['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $apiPasswordVerify = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/users/' . $username . '/password/validate');
        }

        if (isset($apiPasswordVerify['Message']) && $apiPasswordVerify['Message'] == "Password Correct") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Permet de s'enregistrer
     *
     * @param array $data
     * @return array<mixed>
     */
    public function registerUser(array $data): array
    {
        return $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/users/register');
    }

    /**
     * Permet de récupérer un utilisateur
     *
     * @param string $username
     * @return string
     */
    public function getUser(string $username): array
    {
        $user = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/users/' . $username);

        if (isset($user['Message']) && $user['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $user = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/users/' . $username);
        }

        return $user;
    }

    /**
     * Retourne la liste des utilisateurs
     *
     * @param integer $page
     * @return array<mixed>
     */
    public function getAllUsers(int $page = 0): array
    {
        $user = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/users?page=' . $page . '&pageSize=30');
        if (isset($user['Message']) && $user['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $user = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/users?page=' . $page . '&pageSize=30');
        }

        return $user;
    }

    /**
     * Retourne la liste des utilisateurs
     *
     * @return array<mixed>
     */
    public function multipleGetUsers(): array
    {
        $all_players = $this->getAllUsers(0);
        $total = $all_players['Total'];
        $total_page = floor($total / 100);

        if ($total_page == 0) {
            return $this->getAllUsers()["Values"];
        } else {
            $nodes = [];
            $results =  [];
            for ($i = 0; $i < $total_page; $i++) {
                $nodes[] = $this->getServer() . '/api/v1/users?page=' . $i . '&pageSize=100';
            }

            $node_count = count($nodes);

            $curl_arr = array();
            $master = curl_multi_init();

            for ($i = 0; $i < $node_count; $i++) {
                $url = $nodes[$i];
                $curl_arr[$i] = curl_init($url);
                curl_setopt_array($curl_arr[$i], array(
                    CURLOPT_POST => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT => 0,
                    CURLOPT_TIMEOUT_MS => 0,
                    CURLOPT_HTTPHEADER => array(
                        'authorization:Bearer ' . $this->getToken(), // "authorization:Bearer", et non pas "authorization: Bearer"
                        'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                    ),
                ));
                curl_multi_add_handle($master, $curl_arr[$i]);
            }

            do {
                curl_multi_exec($master, $running);
            } while ($running > 0);


            for ($i = 0; $i < $node_count; $i++) {
                $results[] = json_decode(curl_multi_getcontent($curl_arr[$i]), true);
            }

            if (isset($results[0]['Message'])) {
                $this->setToken();
                $all_players = $this->getAllUsers(0);
                $total = $all_players['Total'];
                $total_page = floor($total / 100);
                $results =  [];


                $nodes = [];

                for ($i = 0; $i < $total_page; $i++) {
                    $nodes[] = $this->getServer() . '/api/v1/users?page=' . $i . '&pageSize=100';
                }

                $node_count = count($nodes);

                $curl_arr = array();
                $master = curl_multi_init();

                for ($i = 0; $i < $node_count; $i++) {
                    $url = $nodes[$i];
                    $curl_arr[$i] = curl_init($url);
                    curl_setopt_array($curl_arr[$i], array(
                        CURLOPT_POST => false,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CONNECTTIMEOUT => 0,
                        CURLOPT_TIMEOUT_MS => 0,
                        CURLOPT_HTTPHEADER => array(
                            'authorization:Bearer ' . $this->getToken(), // "authorization:Bearer", et non pas "authorization: Bearer"
                            'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                        ),
                    ));
                    curl_multi_add_handle($master, $curl_arr[$i]);
                }

                do {
                    curl_multi_exec($master, $running);
                } while ($running > 0);


                for ($i = 0; $i < $node_count; $i++) {
                    $results[] = json_decode(curl_multi_getcontent($curl_arr[$i]), true);
                }
                $allPlayers = [];

                foreach ($results as $key => $item) {
                    for ($i = 0; $i < 100; $i++) {
                        $allPlayers[] = $item['Values'][$i];
                    }
                }

                return $allPlayers;
            } else {
                $allPlayers = [];

                foreach ($results as $key => $item) {
                    for ($i = 0; $i < 100; $i++) {
                        $allPlayers[] = $item['Values'][$i];
                    }
                }
                return $allPlayers;
            }
        }
    }

    /**
     * Permet de changer l'email d'un compte
     *
     * @param array $data
     * @param string $user_id
     * @return boolean
     */
    public function changeEmailAccount(array $data, string $user_id): bool
    {
        $user = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/users/' . $user_id . '/email/change');
        if (isset($user['Message']) && $user['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $user = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/users/' . $user_id . '/email/change');
        }
        if (isset($user['Id'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Permet de changer le mot de passe d'un compte
     *
     * @param array $data
     * @param string $user_id
     * @return boolean
     */
    public function changePasswordAccount(array $data, string $user_id): bool
    {
        $user = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/users/' . $user_id . '/manage/password/change');
        if (isset($user['Message']) && $user['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $user = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/users/' . $user_id . '/manage/password/change');
        }

        if ($user['Message'] == "Password Updated." || $user['Message'] == "Password Correct") {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Permet de récupérer les classes du jeu
     *
     * @param array $data
     * @return array<mixed>
     */
    public function getGameClass(array $data): array
    {
        $classes = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/gameobjects/class');

        if (isset($classes['Message']) && $classes['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $classes = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/gameobjects/class');
        }

        return $classes;
    }

    /**
     * Permet de récupérer un personnage
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getCharacter(string $id): array
    {
        $players = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/' . $id);

        if (isset($players['Message']) && $players['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $players = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/' . $id);
        }
        return $players;
    }

    /**
     * Permet de récupérer la liste des personnages d'un compte
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getCharacters(string $id): array
    {
        $players = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/users/' . $id . '/players');
        if (isset($players['Message']) && $players['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $players = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/users/' . $id . '/players');
        }
        return $players;
    }

    /**
     * Permet de récupérer la liste des joueurs
     *
     * @param integer $page
     * @return array<mixed>
     */
    public function getAllPlayers(int $page): array
    {

        $joueurs = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players?page=' . $page . '&pageSize=30');
        if (isset($joueurs['Message']) && $joueurs['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $joueurs = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players?page=' . $page . '&pageSize=30');
        }
        return $joueurs;
    }


    /**
     * Permet de récupérer la liste des joueurs
     *
     * @return array<mixed>
     */
    public function multipleGetPlayers(): array
    {
        $all_players = $this->getAllPlayers(0);
        $total = $all_players['Total'];
        $total_page = floor($total / 100);
        $results = [];
        $nodes = [];

        if ($total_page == 0) {
            return $this->getAllPlayers(0)["Values"];
        } else {
            for ($i = 0; $i < $total_page; $i++) {
                $nodes[] = $this->getServer() . '/api/v1/players?page=' . $i . '&pageSize=100';
            }
            $node_count = count($nodes);
            $curl_arr = array();
            $master = curl_multi_init();

            for ($i = 0; $i < $node_count; $i++) {
                $url = $nodes[$i];
                $curl_arr[$i] = curl_init($url);
                curl_setopt_array($curl_arr[$i], array(
                    CURLOPT_POST => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT => 0,
                    CURLOPT_TIMEOUT_MS => 0,
                    CURLOPT_HTTPHEADER => array(
                        'authorization:Bearer ' . $this->getToken(), // "authorization:Bearer", et non pas "authorization: Bearer"
                        'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                    ),
                ));
                curl_multi_add_handle($master, $curl_arr[$i]);
            }

            do {
                curl_multi_exec($master, $running);
            } while ($running > 0);


            for ($i = 0; $i < $node_count; $i++) {
                $results[] = json_decode(curl_multi_getcontent($curl_arr[$i]), true);
            }

            if (isset($results[0]['Message'])) {
                $this->setToken();
                $all_players = $this->getAllPlayers(0);
                $total = $all_players['Total'];
                $total_page = floor($total / 100);


                $nodes = [];

                for ($i = 0; $i < $total_page; $i++) {
                    $nodes[] = $this->getServer() . '/api/v1/players?page=' . $i . '&pageSize=100';
                }

                $node_count = count($nodes);

                $curl_arr = array();
                $master = curl_multi_init();

                for ($i = 0; $i < $node_count; $i++) {
                    $url = $nodes[$i];
                    $curl_arr[$i] = curl_init($url);
                    curl_setopt_array($curl_arr[$i], array(
                        CURLOPT_POST => false,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CONNECTTIMEOUT => 0,
                        CURLOPT_TIMEOUT_MS => 0,
                        CURLOPT_HTTPHEADER => array(
                            'authorization:Bearer ' . $this->getToken(), // "authorization:Bearer", et non pas "authorization: Bearer"
                            'Content-Type:application/json' // "Content-Type:application/json", et non pas "Content-Type: application/json"
                        ),
                    ));
                    curl_multi_add_handle($master, $curl_arr[$i]);
                }

                do {
                    curl_multi_exec($master, $running);
                } while ($running > 0);


                for ($i = 0; $i < $node_count; $i++) {
                    $results[] = json_decode(curl_multi_getcontent($curl_arr[$i]), true);
                }
                $allPlayers = [];

                foreach ($results as $key => $item) {
                    for ($i = 0; $i < 100; $i++) {
                        $allPlayers[] = $item['Values'][$i];
                    }
                }

                return $allPlayers;
            } else {

                $allPlayers = [];

                foreach ($results as $key => $item) {

                    for ($i = 0; $i < 100; $i++) {
                        $allPlayers[] = $item['Values'][$i];
                    }
                }
                return $allPlayers;
            }
        }
    }


    /**
     * Permet de récupérer la liste des guildes
     *
     * @param integer $page
     * @return array<mixed>
     */
    public function getAllGuilds(int $page = 0): array
    {
        $guildsLists = [];

        $guilds = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/guilds/');
        if (isset($guilds['Message']) && $guilds['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $guilds = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/guilds/');
        }

        foreach ($guilds["Values"] as $guild) {
            $guildsLists[] = $this->getGuild($guild["Key"]["Id"]);
        }

        return ["Total" => $guilds["Total"], "Values" => $guildsLists];
    }

    /**
     * Permet de récupérer une guilde
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getGuild(string $id): array
    {
        $guild = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/guilds/' . $id);
        if (isset($guild['Message']) && $guild['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $guild = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/guilds/' . $id);
        }

        return [
            "guildInfo" => $guild,
            "members" => $this->getGuildMember($guild["Id"])
        ];
    }

    /**
     * Permet de récupérer la liste des joueurs d'une guilde
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getGuildMember(string $id): array
    {
        $guildMember = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/guilds/' . $id . '/members?pageSize=100');
        if (isset($guildMember['Message']) && $guildMember['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $guildMember = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/guilds/' . $id . '/members?pageSize=100');
        }

        return $guildMember["Values"];
    }

    /**
     * Permet de savoir si l'inventaire du joueur est complet
     *
     * @param string $id
     * @return boolean
     */
    public function isInventoryFull(string $id): bool
    {
        $inventory = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/' . $id . '/items/inventory');

        if ($this->find_key_value($inventory, 'ItemId', '00000000-0000-0000-0000-000000000000')) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Permet de récupérer l'inventaire d'un joueur
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getInventory(string $id): array
    {
        $inventory = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/' . $id . '/items/inventory');

        if (isset($inventory['Message']) && $inventory['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $inventory = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/' . $id . '/items/inventory');
        }

        return $inventory;
    }

    /**
     * Permet de récupérer la banque d'un joueur
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getBank(string $id): array
    {
        $inventory = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/' . $id . '/items/bank');

        if (isset($inventory['Message']) && $inventory['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $inventory = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/' . $id . '/items/bank');
        }

        return $inventory;
    }


    /**
     * Permet de récupéré l'inventaire d'un sac
     *
     * @param string $id
     * @return array
     */
    public function getBag(string $id): array
    {
        $inventory = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/bag/' . $id);
        if (isset($inventory['Message']) && $inventory['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $inventory = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/bag/' . $id);
        }

        return $inventory;
    }

    /**
     * Permet de récupérer la liste des joueurs en ligne
     *
     * @param integer $page
     * @return array<mixed>
     */
    public function onlinePlayers(int $page = 0): array
    {
        $data = [
            'page' => $page,
            'size' => 100
        ];
        $online = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/players/online');
        if (isset($online['Message']) && $online['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $online = $this->APIcall_POST($this->getServer(), $data, $this->getToken(), '/api/v1/players/online');
        }
        if (isset($online['entries'])) {
            return $online['entries'];
        } else {
            return [];
        }
    }


    /**
     * Permet de récupérer la liste des items
     *
     * @param integer $page
     * @return array<mixed>
     */
    public function getAllItems(int $page = 0): array
    {
        $data = [
            'page' => $page,
            'count' => 20
        ];

        $items = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/gameobjects/item/');
        if (isset($items['Message']) && $items['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $items = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/gameobjects/item/');
        }
        return $items;
    }

    /**
     * Permet de récupérer les détails d'un item
     * @param string $id
     * @return array<mixed>
     */

    public function getObjectDetail(string $id): array
    {
        $itemData = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/gameobjects/item/' . $id);
        $itemData = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/gameobjects/item/' . $id);
        if (isset($itemData['Message']) && $itemData['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $itemData = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/gameobjects/item/' . $id);
        }
        return $itemData;
    }

    /**
     * Récupère le classement général de l'API
     * @return array<mixed>
     */

    public function getRank(int $page = 0): array
    {
        $joueurs = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/rank?page=' . $page . '&pageSize=25&sortDirection=Descending');
        if (isset($joueurs['Message']) && $joueurs['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $joueurs = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/players/rank?page=' . $page . '&pageSize=25&sortDirection=Descending');
        }
        return $joueurs['Values'];
    }


    /**
     * Permet de donner un objet au personnage
     * @param array<mixed> $data
     * @param string $character
     * @return bool
     */
    public function giveItem(array $data, string $character): bool
    {
        $item = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/players/' . $character . '/items/give');
        if (isset($item['Message']) && $item['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $item = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/players/' . $character . '/items/give');
        }

        if (isset($item['id'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Permet de prendre un objet du personnage
     * @param array<mixed> $data
     * @param string $character
     * @return bool
     */

    public function takeItem(array $data, string $character): bool
    {
        $item = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/players/' . $character . '/items/take');
        if (isset($item['Message']) && $item['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $item = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/players/' . $character . '/items/take');
        }

        if (isset($item['id'])) {
            return true;
        } else {
            return false;
        }
    }


    // Admin Action

    /**
     * Permet de bannir un compte
     *
     * @param string $user_id
     * @param string $username
     * @param integer $duration
     * @param string $moderator
     * @return boolean
     */
    public function banAccount(string $user_id, string $username, int $duration = 5, string $moderator = "Game Admin"): bool
    {
        $data = [
            'duration' => $duration,
            'reason' => "Ban from web interface",
            'moderator' => $moderator
        ];
        $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/ban');
        if (isset($ban['Message']) && $ban['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/ban');
        }

        if (isset($ban['Message']) && $ban['Message'] == $username . ' has been banned!') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Permet de débannir un compte
     *
     * @param string $user_id
     * @param string $username
     * @return boolean
     */
    public function unBanAccount(string $user_id, string $username): bool
    {
        $data = [
            'username' => $username
        ];

        $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/unban');
        if (isset($ban['Message']) && $ban['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/unban');
        }

        if (isset($ban['Message']) && $ban['Message'] == 'Account ' . $username . ' has been unbanned!') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Permet de muter un compte
     *
     * @param string $user_id
     * @param string $username
     * @param integer $duration
     * @param string $moderator
     * @return boolean
     */
    public function MuteAccount(string $user_id, string $username, int $duration = 5, string $moderator = "Game Admin"): bool
    {
        $data = [
            'duration' => $duration,
            'reason' => "Mute from web interface",
            'moderator' => $moderator
        ];
        $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/mute');
        if (isset($ban['Message']) && $ban['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/mute');
        }

        if (isset($ban['Message']) && $ban['Message'] == $username . ' has been muted!') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Permet de démuter un compte
     *
     * @param string $user_id
     * @param string $username
     * @return boolean
     */
    public function unMuteAccount(string $user_id, string $username): bool
    {
        $data = [
            'username' => $username
        ];

        $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/unmute');
        if (isset($ban['Message']) && $ban['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $ban = $this->APIcall_POST($this->getServer(), $data,  $this->getToken(), '/api/v1/users/' . $user_id . '/admin/unmute');
        }

        if (isset($ban['Message']) && $ban['Message'] == $username . ' has been unmuted!') {
            return true;
        } else {
            return false;
        }
    }


    // Server Data

    /**
     * Permet de récupérer les infos du serveur
     *
     * @return array<mixed>
     */
    public function getServerInfo(): array
    {
        $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/info/stats');
        if (isset($server['Message']) && $server['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/info/stats');
        }
        return $server;
    }

    /**
     * Permet de récupérer les statistiques du serveur
     *
     * @return array<mixed>
     */
    public function getServerMetrics(): array
    {
        $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/info/metrics');
        if (isset($server['Message']) && $server['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/info/metrics');
        }
        return $server;
    }

    // Server Log

    /**
     * Permet de récupérer les logs d'activité d'un utilisateur
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getUserActivity(string $id): array
    {
        $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/user/' . $id . '/activity');
        if (isset($server['Message']) && $server['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/user/' . $id . '/activity');
        }
        return $server;
    }

    /**
     * Permet de récupérer les logs d'activité d'un personnage
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getPlayerActivity(string $id): array
    {
        $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/player/' . $id . '/activity');
        if (isset($server['Message']) && $server['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/player/' . $id . '/activity');
        }
        return $server;
    }

    /**
     * Permet de récupérer les logs d'activité des trades
     *
     * @return array<mixed>
     */
    public function getTradeLogs(): array
    {
        $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/trade/');
        if (isset($server['Message']) && $server['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/trade/');
        }
        return $server;
    }

    /**
     * Permet de récupérer l'ip d'un utilisateur
     *
     * @param string $id
     * @return array<mixed>
     */
    public function getUserIp(string $id): array
    {
        $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/user/' . $id . '/ip');
        if (isset($server['Message']) && $server['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server = $this->APIcall_GET($this->getServer(), $this->getToken(), '/api/v1/logs/user/' . $id . '/ip');
        }
        return $server;
    }


    // Discord API


    // Configuration Serveur

    public function getServerConfig()
    {
        $server = $this->APIcall_GET($this->getServer(),  $this->getToken(), '/api/v1/info/config');
        if (isset($server['Message']) && $server['Message'] == "Authorization has been denied for this request.") {
            $this->setToken();
            $server = $this->APIcall_GET($this->getServer(),  $this->getToken(), '/api/v1/info/config');
        }

        return $server;
    }


    /**
     * Get the value of server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

    public function getDedipassPublic()
    {
        return $this->dedipass_public;
    }

    public function getDedipassPrivate()
    {
        return $this->dedipass_private;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken()
    {
        $loginAPI = $this->APIcall_POST($this->getServer(), $this->getApiData(), "", '/api/oauth/token');
        $newToken = $this->em->getRepository(CmsSettings::class)->findOneBy(['setting' => 'api_token']);
        $newToken->setDefaultValue($loginAPI['access_token']);
        $this->em->persist($newToken);
        $this->em->flush();


        $this->token = $newToken->getDefaultValue();

        return $this;
    }


    function find_key_value(array $array, string $key, string $val)
    {
        foreach ($array as $item) {
            if (is_array($item) && $this->find_key_value($item, $key, $val)) return true;

            if (isset($item[$key]) && $item[$key] == $val) return true;
        }

        return false;
    }
}
