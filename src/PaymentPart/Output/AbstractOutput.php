<?php

namespace Sprain\SwissQrBill\PaymentPart\Output;

use Sprain\SwissQrBill\DataGroup\Element\PaymentReference;
use Sprain\SwissQrBill\PaymentPart\Output\Element\Placeholder;
use Sprain\SwissQrBill\PaymentPart\Output\Element\Text;
use Sprain\SwissQrBill\PaymentPart\Output\Element\Title;
use Sprain\SwissQrBill\QrBill;

abstract class AbstractOutput
{
    /** @var QrBill */
    protected $qrBill;

    /** @var  string */
    protected $language;

    public function __construct(QrBill $qrBill, string $language)
    {
        $this->qrBill = $qrBill;
        $this->language = $language;
    }

    public function getQrBill() : ?QrBill
    {
        return $this->qrBill;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    protected function getInformationElements() : array
    {
        $informationElements = [];

        $informationElements[] = Title::create('text.creditor');
        $informationElements[] = Text::create($this->qrBill->getCreditorInformation()->getFormattedIban() . "\n" . $this->qrBill->getCreditor()->getFullAddress());

        if ($this->qrBill->getPaymentReference()->getType() !== PaymentReference::TYPE_NON) {
            $informationElements[] = Title::create('text.reference');
            $informationElements[] = Text::create($this->qrBill->getPaymentReference()->getFormattedReference());
        }

        if ($this->qrBill->getAdditionalInformation()) {
            $informationElements[] = Title::create('text.additionalInformation');
            $informationElements[] = Text::create($this->qrBill->getAdditionalInformation()->getMessage());
        }

        if ($this->qrBill->getUltimateDebtor()) {
            $informationElements[] = Title::create('text.payableBy');
            $informationElements[] = Text::create($this->qrBill->getUltimateDebtor()->getFullAddress());
        } else {
            $informationElements[] = Title::create('text.payableByName');
            $informationElements[] = Placeholder::create(Placeholder::PLACEHOLDER_TYPE_PAYABLE_BY);
        }

        return $informationElements;
    }

    protected function getInformationElementsOfReceipt() : array
    {
        $informationElements = [];

        $informationElements[] = Title::create('text.creditor');
        $informationElements[] = Text::create($this->qrBill->getCreditorInformation()->getFormattedIban() . "\n" . $this->qrBill->getCreditor()->getFullAddress());

        if ($this->qrBill->getPaymentReference()->getType() !== PaymentReference::TYPE_NON) {
            $informationElements[] = Title::create('text.reference');
            $informationElements[] = Text::create($this->qrBill->getPaymentReference()->getFormattedReference());
        }

        if ($this->qrBill->getUltimateDebtor()) {
            $informationElements[] = Title::create('text.payableBy');
            $informationElements[] = Text::create($this->qrBill->getUltimateDebtor()->getFullAddress());
        } else {
            $informationElements[] = Title::create('text.payableByName');
            $informationElements[] = Placeholder::create(Placeholder::PLACEHOLDER_TYPE_PAYABLE_BY_RECEIPT);
        }

        return $informationElements;
    }
}