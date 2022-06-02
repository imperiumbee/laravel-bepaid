<?php

namespace Excent\BePaidLaravel;

use BeGateway\{QueryByPaymentToken, QueryByTrackingId, QueryByUid};
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\{QueryByPaymentTokenDto, QueryByTrackingIdDto, QueryByUidDto};

class Query extends GatewayAbstract
{
    public QueryByPaymentToken|QueryByTrackingId|QueryByUid $operation;

    private QueryByPaymentToken $queryByPaymentToken;

    private QueryByTrackingId $queryByTrackingId;

    private QueryByUid $queryByUuid;

    public function __construct(
        QueryByPaymentToken $queryByPaymentToken,
        QueryByTrackingId $queryByTrackingId,
        QueryByUid $queryByUid
    )
    {
        $this->queryByPaymentToken = $queryByPaymentToken;
        $this->queryByTrackingId = $queryByTrackingId;
        $this->queryByUuid = $queryByUid;
    }

    /**
     * @param QueryByPaymentTokenDto|QueryByTrackingIdDto|QueryByUidDto|array    $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Product $object
     *
     * @return \Excent\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        if (is_array($data)) {
            if (array_key_exists('token', $data)) {
                $this->operation = $this->queryByPaymentToken;
            } elseif (array_key_exists('tracking_id', $data)) {
                $this->operation = $this->queryByTrackingId;
            } elseif (array_key_exists('uid', $data)) {
                $this->operation = $this->queryByUuid;
            }
        } else {
            switch (get_class($data)) {
                case QueryByPaymentTokenDto::class:
                    $this->operation = $this->queryByPaymentToken;
                    break;
                case QueryByTrackingIdDto::class:
                    $this->operation = $this->queryByTrackingId;
                    break;
                case QueryByUidDto::class:
                    $this->operation = $this->queryByUuid;
                    break;
            }
        }

        return parent::fill($data, $object);
    }
}