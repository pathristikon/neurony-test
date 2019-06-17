<?php

namespace App\Services;

use Elasticsearch\Client;
use App\Contracts\ElasticPostSearch;
use App\Post;
use Elasticsearch;
use Illuminate\Database\Eloquent\Collection;

class ElasticSearchPostService implements ElasticPostSearch
{

    private $client;
    const INDEX = 'test';
    const TYPE  = '_doc';

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->bootPosts();
        $this->populateElastic();
    }

    /**
     * Create the structure for posts
     */
    private function bootPosts()
    {
        $checkIndex = [
            'index' => 'test'
        ];

        if(!$this->client->indices()->exists($checkIndex))
        {
            $params = [
                'index' => $this::INDEX,
                'body' => [
                    'settings' => [
                        'number_of_shards' => 1,
                        'number_of_replicas' => 1
                    ],
                    'mappings' => [
                        'properties' => [
                            'id' => [
                                'type' => 'integer'
                            ],
                            'name' => [
                                'type' => 'keyword'
                            ],
                            'content' => [
                                'type' => 'text'
                            ],
                            'active' => [
                                'type' => 'boolean'
                            ],
                            'created_at' => [
                                'type' => 'text',
                                'fielddata' => 'true'
                            ],
                            'updated_at' => [
                                'type' => 'text'
                            ]
                        ]
                    ]
                ]
            ];

            $this->client->indices()->create($params);
        }
    }

    /**
     * Populate elasticsearch on init
     */
    private function populateElastic()
    {
        foreach(Post::all() as $post)
        {
            $data = [
                'body' => $post,
                'index' => $this::INDEX,
                'type' => $this::TYPE,
                'id' => $post['id'],
            ];

            Elasticsearch::index($data);
        }
    }

    /**
     * Hydrate posts from response
     * @param $response
     * @return Collection
     */
    private function hydratePostsElastic($response): Collection
    {
        $posts = $this->getESResponseSource($response);
        return Post::hydrate($posts);
    }

    /**
     * Filter ES response
     * @param array $items
     * @return array
     */
    private function getESResponseSource(array $items)
    {
        return array_pluck($items['hits']['hits'], '_source') ?: [];
    }

    /**
     * Create the ES query
     * @param string $index
     * @param string $type
     * @param array $body
     * @return Collection
     */
    public function createQueryElastic(array $body)
    {
        $query = [
            'index' => $this::INDEX,
            'type'  => $this::TYPE,
            'body'  => $body,
            'from' => 0,
            'size' => 10000
        ];

        return $this->doSearch($query);
    }

    /**
     * This does the actual search
     * @param $params
     * @return Collection
     */
    private function doSearch($params): Collection
    {
        $es =  Elasticsearch::search($params);
        return $this->hydratePostsElastic($es);
    }
}