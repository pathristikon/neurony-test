<?php

namespace App\Repositories;

use App\Contracts\SearchableContract;
use Illuminate\Database\Eloquent\Collection;
use App\Contracts\ElasticPostSearch;
use App\Traits\NotifiesPostSearches;

class ElasticsearchPostSearchRepository implements SearchableContract
{

    use NotifiesPostSearches {
        NotifiesPostSearches::sendNewPostSearchNotifications as sNewPostSearchNotifications;
    }

    protected $query;

    /**
     * @var ElasticPostSearch
     */
    private $searchEs;

    /**
     * @return void
     */
    public function __construct(ElasticPostSearch $searchEs)
    {
        $this->searchEs = $searchEs;
    }

    /**
     * @param null|string $keyword
     * @return SearchableContract
     */
    public function search(?string $keyword = null) : SearchableContract
    {
        if ($keyword) {
            $this->query = $this->searchEs->createQueryElastic([
               'query' => [
                   'multi_match' => [
                       'fields' => ['name', 'content'],
                       'query' => $keyword,
                   ],
               ]
           ]);
        }

        return $this;
    }

    /**
     * @return SearchableContract
     */
    public function active() : SearchableContract
    {
        $this->query = $this->searchEs->createQueryElastic([
            'query' => [
                'multi_match' => [
                    'fields' => ['active'],
                    'query' => true,
                ],
            ]
        ]);

        return $this;
    }

    /**
     * @return SearchableContract
     */
    public function inactive() : SearchableContract
    {
        $this->query = $this->searchEs->createQueryElastic([
            'query' => [
                'multi_match' => [
                    'fields' => ['active'],
                    'query' => false,
                ],
            ]
        ]);

        return $this;
    }

    /**
     * @return SearchableContract
     */
    public function alphabetically() : SearchableContract
    {
        $this->query = $this->searchEs->createQueryElastic([
            'sort' => [
                'name' => 'asc',
            ]
        ]);

        return $this;
    }

    /**
     * @return SearchableContract
     */
    public function latest() : SearchableContract
    {
        $this->query = $this->searchEs->createQueryElastic([
            'sort' => [
                'created_at' => 'desc',
            ]
        ]);

        return $this;
    }

    /**
     * @return Collection
     */
    public function fetch() : Collection
    {
        if(isset($this->query))
        {
            $this->sNewPostSearchNotifications($this->query);
            return $this->query;
        }

        $posts =  $this->searchEs->createQueryElastic([
            'query' => [
                'match_all' => (object)[],
            ]
        ]);

        $this->sNewPostSearchNotifications($posts);

        return $posts;
    }

    /**
     * @return string
     */
    public function sendNewPostSearchNotifications() : string
    {
        return 'this is a dummy';
    }

}