<?php

namespace QcloudIM\Api;

use QcloudIM\Constants;
use QcloudIM\Model\AddFriendItem;
use QcloudIM\Traits\HttpClientTrait;

/**
 * 关系链管理(好友)
 */
class Friend
{
    use HttpClientTrait;

    /**
     * 添加好友
     *
     * @param string        $fromAccountId
     * @param AddFriendItem $item
     * @param bool          $forceAddFlags 管理员强制加好友标记：1表示强制加好友，0表示常规加好友方式
     * @param string        $addType       加好友方式（默认双向加好友方式）：
     *                                     Add_Type_Single 表示单向加好友
     *                                     Add_Type_Both 表示双向加好友
     *
     * @return array
     */
    public function add(
        string $fromAccountId,
        AddFriendItem $item,
        bool $forceAddFlags = false,
        string $addType = Constants::FRIEND_ADD_TYPE_BOTH
    ): array {
        $p = [
            'From_Account' => $fromAccountId,
            'ForceAddFlags' => $forceAddFlags ? 1 : 0,
            'AddType' => $addType,
            'AddFriendItem' => [
                (array)$item
            ],
        ];
        return $this->httpClient->postJson('sns/friend_add', $p);
    }

    /**
     * 批量添加好友
     *
     * @param string $fromAccountId
     * @param array  $friendItems
     * @param bool   $forceAddFlags
     * @param string $addType
     *
     * @return array
     */
    public function batchAdd(
        string $fromAccountId,
        array $friendItems,
        bool $forceAddFlags = false,
        string $addType = Constants::FRIEND_ADD_TYPE_BOTH
    ): array {
        return $this->httpClient->postJson('sns/friend_add', [
            'From_Account' => $fromAccountId,
            'ForceAddFlags' => $forceAddFlags ? 1 : 0,
            'AddType' => $addType,
            'AddFriendItem' => (array)$friendItems,
        ]);
    }

    /**
     * 拉取好友
     *
     * @param string $fromAccountId
     * @param int    $StartIndex       分页的起始位置
     * @param int    $StandardSequence 上次拉好友数据时返回的 StandardSequence，如果 StandardSequence 字段的值与后台一致，后台不会返回标配好友数据
     * @param int    $CustomSequence   上次拉好友数据时返回的 CustomSequence，如果 CustomSequence 字段的值与后台一致，后台不会返回自定义好友数据
     *
     * @return array
     */
    public function get(
        string $fromAccountId,
        int $StartIndex,
        int $StandardSequence = 0,
        int $CustomSequence = 0
    ): array {
        $p = [
            'From_Account' => $fromAccountId,
            'StartIndex' => $StartIndex,
        ];
        empty($StandardSequence) or $p['StandardSequence'] = $StandardSequence;
        empty($CustomSequence) or $p['CustomSequence'] = $CustomSequence;
        return $this->httpClient->postJson('sns/friend_get', $p);
    }

    /**
     * 批量导入好友
     *
     * @param string $fromAccountId
     * @param array  $friendItems
     *
     * @return array
     */
    public function import(
        string $fromAccountId,
        array $friendItems
    ): array {
        return $this->httpClient->postJson('sns/friend_import', [
            'From_Account' => $fromAccountId,
            'AddFriendItem' => (array)$friendItems,
        ]);
    }

}
