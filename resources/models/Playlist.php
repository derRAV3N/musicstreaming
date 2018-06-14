<?php
/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 06.06.18
 * Time: 11:44
 */

class Playlist {
    protected $id;
    protected $name;
    protected $created;
    protected $userId;
    protected $songIds;

    /**
     * Playlist constructor.
     * @param $id
     */
    public function __construct($id) {
        $this->setId($id);

        $albumData = DBConnect::getPlaylistAttributes($this->getId());

        $this->setName($albumData['name']);
        $this->setCreated($albumData['created']);
        $this->setUserId($albumData['userId']);
        $this->setSongIds(DBConnect::getSongsFromPlaylist($this->getId()));
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param integer $id
     */
    public function setUserId($id) {
        $this->userId = $id;
    }

    /**
     * @return array
     */
    public function getSongIds() {
        return $this->songIds;
    }

    /**
     * @param array $songIds
     */
    public function setSongIds($songIds) {
        $this->songIds = $songIds;
    }

    /**
     * @param integer $songId
     */
    public function addSongId($songId) {
        $this->songIds[] = $songId;
    }



}