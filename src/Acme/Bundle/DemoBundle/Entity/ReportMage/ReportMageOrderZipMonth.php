<?php

namespace Acme\Bundle\DemoBundle\Entity\ReportMage;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="oro_report_mage_order_zip_month", indexes={@ORM\Index(name="IDX_COUNTRY_ZIP", columns={"country_id", "zip"}), @ORM\Index(name="IDX_YEAR_MONTH", columns={"year", "month"})})
 * @ORM\Entity
 */
class ReportMageOrderZipMonth
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="country_id", type="string", length=2, nullable=true)
     */
    protected $countryId;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=255, nullable=true)
     */
    protected $zip;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="smallint", nullable=true)
     */
    protected $year = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="month", type="smallint", nullable=true)
     */
    protected $month = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_count", type="smallint", nullable=true)
     */
    protected $orderCount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_discount_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseDiscountAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_discount_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseDiscountAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_discount_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseDiscountAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_discount_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseDiscountAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_discount_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseDiscountCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_discount_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseDiscountCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_discount_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseDiscountCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_discount_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseDiscountCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_discount_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseDiscountInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_discount_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseDiscountInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_discount_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseDiscountInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_discount_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseDiscountInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_discount_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseDiscountRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_discount_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseDiscountRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_discount_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseDiscountRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_discount_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseDiscountRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_grand_total", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseGrandTotal = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_grand_total", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseGrandTotal = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_grand_total", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseGrandTotal = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_grand_total", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseGrandTotal = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_shipping_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseShippingAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_shipping_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseShippingAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_shipping_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseShippingAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_shipping_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseShippingAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_shipping_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseShippingCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_shipping_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseShippingCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_shipping_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseShippingCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_shipping_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseShippingCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_shipping_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseShippingInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_shipping_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseShippingInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_shipping_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseShippingInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_shipping_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseShippingInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_shipping_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseShippingRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_shipping_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseShippingRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_shipping_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseShippingRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_shipping_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseShippingRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_tax_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseTaxAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_tax_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseTaxAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_tax_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseTaxAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_tax_amount", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseTaxAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_tax_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseTaxCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_tax_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseTaxCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_tax_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseTaxCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_tax_canceled", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseTaxCanceled = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_tax_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseTaxInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_tax_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseTaxInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_tax_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseTaxInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_tax_invoiced", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseTaxInvoiced = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="min_base_tax_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $minBaseTaxRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="max_base_tax_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $maxBaseTaxRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_base_tax_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $avgBaseTaxRefunded = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sum_base_tax_refunded", type="float", precision=10, scale=0, nullable=true)
     */
    protected $sumBaseTaxRefunded = 0;

    /**
     * Set countryId
     *
     * @param string $countryId
     *
     * @return ReportMageOrderZipMonth
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return string
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return ReportMageOrderZipMonth
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return ReportMageOrderZipMonth
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set orderCount
     *
     * @param integer $orderCount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setOrderCount($orderCount)
    {
        $this->orderCount = $orderCount;

        return $this;
    }

    /**
     * Get orderCount
     *
     * @return integer
     */
    public function getOrderCount()
    {
        return $this->orderCount;
    }

    /**
     * Set minBaseDiscountAmount
     *
     * @param float $minBaseDiscountAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseDiscountAmount($minBaseDiscountAmount)
    {
        $this->minBaseDiscountAmount = $minBaseDiscountAmount;

        return $this;
    }

    /**
     * Get minBaseDiscountAmount
     *
     * @return float
     */
    public function getMinBaseDiscountAmount()
    {
        return $this->minBaseDiscountAmount;
    }

    /**
     * Set maxBaseDiscountAmount
     *
     * @param float $maxBaseDiscountAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseDiscountAmount($maxBaseDiscountAmount)
    {
        $this->maxBaseDiscountAmount = $maxBaseDiscountAmount;

        return $this;
    }

    /**
     * Get maxBaseDiscountAmount
     *
     * @return float
     */
    public function getMaxBaseDiscountAmount()
    {
        return $this->maxBaseDiscountAmount;
    }

    /**
     * Set avgBaseDiscountAmount
     *
     * @param float $avgBaseDiscountAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseDiscountAmount($avgBaseDiscountAmount)
    {
        $this->avgBaseDiscountAmount = $avgBaseDiscountAmount;

        return $this;
    }

    /**
     * Get avgBaseDiscountAmount
     *
     * @return float
     */
    public function getAvgBaseDiscountAmount()
    {
        return $this->avgBaseDiscountAmount;
    }

    /**
     * Set sumBaseDiscountAmount
     *
     * @param float $sumBaseDiscountAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseDiscountAmount($sumBaseDiscountAmount)
    {
        $this->sumBaseDiscountAmount = $sumBaseDiscountAmount;

        return $this;
    }

    /**
     * Get sumBaseDiscountAmount
     *
     * @return float
     */
    public function getSumBaseDiscountAmount()
    {
        return $this->sumBaseDiscountAmount;
    }

    /**
     * Set minBaseDiscountCanceled
     *
     * @param float $minBaseDiscountCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseDiscountCanceled($minBaseDiscountCanceled)
    {
        $this->minBaseDiscountCanceled = $minBaseDiscountCanceled;

        return $this;
    }

    /**
     * Get minBaseDiscountCanceled
     *
     * @return float
     */
    public function getMinBaseDiscountCanceled()
    {
        return $this->minBaseDiscountCanceled;
    }

    /**
     * Set maxBaseDiscountCanceled
     *
     * @param float $maxBaseDiscountCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseDiscountCanceled($maxBaseDiscountCanceled)
    {
        $this->maxBaseDiscountCanceled = $maxBaseDiscountCanceled;

        return $this;
    }

    /**
     * Get maxBaseDiscountCanceled
     *
     * @return float
     */
    public function getMaxBaseDiscountCanceled()
    {
        return $this->maxBaseDiscountCanceled;
    }

    /**
     * Set avgBaseDiscountCanceled
     *
     * @param float $avgBaseDiscountCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseDiscountCanceled($avgBaseDiscountCanceled)
    {
        $this->avgBaseDiscountCanceled = $avgBaseDiscountCanceled;

        return $this;
    }

    /**
     * Get avgBaseDiscountCanceled
     *
     * @return float
     */
    public function getAvgBaseDiscountCanceled()
    {
        return $this->avgBaseDiscountCanceled;
    }

    /**
     * Set sumBaseDiscountCanceled
     *
     * @param float $sumBaseDiscountCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseDiscountCanceled($sumBaseDiscountCanceled)
    {
        $this->sumBaseDiscountCanceled = $sumBaseDiscountCanceled;

        return $this;
    }

    /**
     * Get sumBaseDiscountCanceled
     *
     * @return float
     */
    public function getSumBaseDiscountCanceled()
    {
        return $this->sumBaseDiscountCanceled;
    }

    /**
     * Set minBaseDiscountInvoiced
     *
     * @param float $minBaseDiscountInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseDiscountInvoiced($minBaseDiscountInvoiced)
    {
        $this->minBaseDiscountInvoiced = $minBaseDiscountInvoiced;

        return $this;
    }

    /**
     * Get minBaseDiscountInvoiced
     *
     * @return float
     */
    public function getMinBaseDiscountInvoiced()
    {
        return $this->minBaseDiscountInvoiced;
    }

    /**
     * Set maxBaseDiscountInvoiced
     *
     * @param float $maxBaseDiscountInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseDiscountInvoiced($maxBaseDiscountInvoiced)
    {
        $this->maxBaseDiscountInvoiced = $maxBaseDiscountInvoiced;

        return $this;
    }

    /**
     * Get maxBaseDiscountInvoiced
     *
     * @return float
     */
    public function getMaxBaseDiscountInvoiced()
    {
        return $this->maxBaseDiscountInvoiced;
    }

    /**
     * Set avgBaseDiscountInvoiced
     *
     * @param float $avgBaseDiscountInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseDiscountInvoiced($avgBaseDiscountInvoiced)
    {
        $this->avgBaseDiscountInvoiced = $avgBaseDiscountInvoiced;

        return $this;
    }

    /**
     * Get avgBaseDiscountInvoiced
     *
     * @return float
     */
    public function getAvgBaseDiscountInvoiced()
    {
        return $this->avgBaseDiscountInvoiced;
    }

    /**
     * Set sumBaseDiscountInvoiced
     *
     * @param float $sumBaseDiscountInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseDiscountInvoiced($sumBaseDiscountInvoiced)
    {
        $this->sumBaseDiscountInvoiced = $sumBaseDiscountInvoiced;

        return $this;
    }

    /**
     * Get sumBaseDiscountInvoiced
     *
     * @return float
     */
    public function getSumBaseDiscountInvoiced()
    {
        return $this->sumBaseDiscountInvoiced;
    }

    /**
     * Set minBaseDiscountRefunded
     *
     * @param float $minBaseDiscountRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseDiscountRefunded($minBaseDiscountRefunded)
    {
        $this->minBaseDiscountRefunded = $minBaseDiscountRefunded;

        return $this;
    }

    /**
     * Get minBaseDiscountRefunded
     *
     * @return float
     */
    public function getMinBaseDiscountRefunded()
    {
        return $this->minBaseDiscountRefunded;
    }

    /**
     * Set maxBaseDiscountRefunded
     *
     * @param float $maxBaseDiscountRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseDiscountRefunded($maxBaseDiscountRefunded)
    {
        $this->maxBaseDiscountRefunded = $maxBaseDiscountRefunded;

        return $this;
    }

    /**
     * Get maxBaseDiscountRefunded
     *
     * @return float
     */
    public function getMaxBaseDiscountRefunded()
    {
        return $this->maxBaseDiscountRefunded;
    }

    /**
     * Set avgBaseDiscountRefunded
     *
     * @param float $avgBaseDiscountRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseDiscountRefunded($avgBaseDiscountRefunded)
    {
        $this->avgBaseDiscountRefunded = $avgBaseDiscountRefunded;

        return $this;
    }

    /**
     * Get avgBaseDiscountRefunded
     *
     * @return float
     */
    public function getAvgBaseDiscountRefunded()
    {
        return $this->avgBaseDiscountRefunded;
    }

    /**
     * Set sumBaseDiscountRefunded
     *
     * @param float $sumBaseDiscountRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseDiscountRefunded($sumBaseDiscountRefunded)
    {
        $this->sumBaseDiscountRefunded = $sumBaseDiscountRefunded;

        return $this;
    }

    /**
     * Get sumBaseDiscountRefunded
     *
     * @return float
     */
    public function getSumBaseDiscountRefunded()
    {
        return $this->sumBaseDiscountRefunded;
    }

    /**
     * Set minBaseGrandTotal
     *
     * @param float $minBaseGrandTotal
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseGrandTotal($minBaseGrandTotal)
    {
        $this->minBaseGrandTotal = $minBaseGrandTotal;

        return $this;
    }

    /**
     * Get minBaseGrandTotal
     *
     * @return float
     */
    public function getMinBaseGrandTotal()
    {
        return $this->minBaseGrandTotal;
    }

    /**
     * Set maxBaseGrandTotal
     *
     * @param float $maxBaseGrandTotal
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseGrandTotal($maxBaseGrandTotal)
    {
        $this->maxBaseGrandTotal = $maxBaseGrandTotal;

        return $this;
    }

    /**
     * Get maxBaseGrandTotal
     *
     * @return float
     */
    public function getMaxBaseGrandTotal()
    {
        return $this->maxBaseGrandTotal;
    }

    /**
     * Set avgBaseGrandTotal
     *
     * @param float $avgBaseGrandTotal
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseGrandTotal($avgBaseGrandTotal)
    {
        $this->avgBaseGrandTotal = $avgBaseGrandTotal;

        return $this;
    }

    /**
     * Get avgBaseGrandTotal
     *
     * @return float
     */
    public function getAvgBaseGrandTotal()
    {
        return $this->avgBaseGrandTotal;
    }

    /**
     * Set sumBaseGrandTotal
     *
     * @param float $sumBaseGrandTotal
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseGrandTotal($sumBaseGrandTotal)
    {
        $this->sumBaseGrandTotal = $sumBaseGrandTotal;

        return $this;
    }

    /**
     * Get sumBaseGrandTotal
     *
     * @return float
     */
    public function getSumBaseGrandTotal()
    {
        return $this->sumBaseGrandTotal;
    }

    /**
     * Set minBaseShippingAmount
     *
     * @param float $minBaseShippingAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseShippingAmount($minBaseShippingAmount)
    {
        $this->minBaseShippingAmount = $minBaseShippingAmount;

        return $this;
    }

    /**
     * Get minBaseShippingAmount
     *
     * @return float
     */
    public function getMinBaseShippingAmount()
    {
        return $this->minBaseShippingAmount;
    }

    /**
     * Set maxBaseShippingAmount
     *
     * @param float $maxBaseShippingAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseShippingAmount($maxBaseShippingAmount)
    {
        $this->maxBaseShippingAmount = $maxBaseShippingAmount;

        return $this;
    }

    /**
     * Get maxBaseShippingAmount
     *
     * @return float
     */
    public function getMaxBaseShippingAmount()
    {
        return $this->maxBaseShippingAmount;
    }

    /**
     * Set avgBaseShippingAmount
     *
     * @param float $avgBaseShippingAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseShippingAmount($avgBaseShippingAmount)
    {
        $this->avgBaseShippingAmount = $avgBaseShippingAmount;

        return $this;
    }

    /**
     * Get avgBaseShippingAmount
     *
     * @return float
     */
    public function getAvgBaseShippingAmount()
    {
        return $this->avgBaseShippingAmount;
    }

    /**
     * Set sumBaseShippingAmount
     *
     * @param float $sumBaseShippingAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseShippingAmount($sumBaseShippingAmount)
    {
        $this->sumBaseShippingAmount = $sumBaseShippingAmount;

        return $this;
    }

    /**
     * Get sumBaseShippingAmount
     *
     * @return float
     */
    public function getSumBaseShippingAmount()
    {
        return $this->sumBaseShippingAmount;
    }

    /**
     * Set minBaseShippingCanceled
     *
     * @param float $minBaseShippingCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseShippingCanceled($minBaseShippingCanceled)
    {
        $this->minBaseShippingCanceled = $minBaseShippingCanceled;

        return $this;
    }

    /**
     * Get minBaseShippingCanceled
     *
     * @return float
     */
    public function getMinBaseShippingCanceled()
    {
        return $this->minBaseShippingCanceled;
    }

    /**
     * Set maxBaseShippingCanceled
     *
     * @param float $maxBaseShippingCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseShippingCanceled($maxBaseShippingCanceled)
    {
        $this->maxBaseShippingCanceled = $maxBaseShippingCanceled;

        return $this;
    }

    /**
     * Get maxBaseShippingCanceled
     *
     * @return float
     */
    public function getMaxBaseShippingCanceled()
    {
        return $this->maxBaseShippingCanceled;
    }

    /**
     * Set avgBaseShippingCanceled
     *
     * @param float $avgBaseShippingCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseShippingCanceled($avgBaseShippingCanceled)
    {
        $this->avgBaseShippingCanceled = $avgBaseShippingCanceled;

        return $this;
    }

    /**
     * Get avgBaseShippingCanceled
     *
     * @return float
     */
    public function getAvgBaseShippingCanceled()
    {
        return $this->avgBaseShippingCanceled;
    }

    /**
     * Set sumBaseShippingCanceled
     *
     * @param float $sumBaseShippingCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseShippingCanceled($sumBaseShippingCanceled)
    {
        $this->sumBaseShippingCanceled = $sumBaseShippingCanceled;

        return $this;
    }

    /**
     * Get sumBaseShippingCanceled
     *
     * @return float
     */
    public function getSumBaseShippingCanceled()
    {
        return $this->sumBaseShippingCanceled;
    }

    /**
     * Set minBaseShippingInvoiced
     *
     * @param float $minBaseShippingInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseShippingInvoiced($minBaseShippingInvoiced)
    {
        $this->minBaseShippingInvoiced = $minBaseShippingInvoiced;

        return $this;
    }

    /**
     * Get minBaseShippingInvoiced
     *
     * @return float
     */
    public function getMinBaseShippingInvoiced()
    {
        return $this->minBaseShippingInvoiced;
    }

    /**
     * Set maxBaseShippingInvoiced
     *
     * @param float $maxBaseShippingInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseShippingInvoiced($maxBaseShippingInvoiced)
    {
        $this->maxBaseShippingInvoiced = $maxBaseShippingInvoiced;

        return $this;
    }

    /**
     * Get maxBaseShippingInvoiced
     *
     * @return float
     */
    public function getMaxBaseShippingInvoiced()
    {
        return $this->maxBaseShippingInvoiced;
    }

    /**
     * Set avgBaseShippingInvoiced
     *
     * @param float $avgBaseShippingInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseShippingInvoiced($avgBaseShippingInvoiced)
    {
        $this->avgBaseShippingInvoiced = $avgBaseShippingInvoiced;

        return $this;
    }

    /**
     * Get avgBaseShippingInvoiced
     *
     * @return float
     */
    public function getAvgBaseShippingInvoiced()
    {
        return $this->avgBaseShippingInvoiced;
    }

    /**
     * Set sumBaseShippingInvoiced
     *
     * @param float $sumBaseShippingInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseShippingInvoiced($sumBaseShippingInvoiced)
    {
        $this->sumBaseShippingInvoiced = $sumBaseShippingInvoiced;

        return $this;
    }

    /**
     * Get sumBaseShippingInvoiced
     *
     * @return float
     */
    public function getSumBaseShippingInvoiced()
    {
        return $this->sumBaseShippingInvoiced;
    }

    /**
     * Set minBaseShippingRefunded
     *
     * @param float $minBaseShippingRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseShippingRefunded($minBaseShippingRefunded)
    {
        $this->minBaseShippingRefunded = $minBaseShippingRefunded;

        return $this;
    }

    /**
     * Get minBaseShippingRefunded
     *
     * @return float
     */
    public function getMinBaseShippingRefunded()
    {
        return $this->minBaseShippingRefunded;
    }

    /**
     * Set maxBaseShippingRefunded
     *
     * @param float $maxBaseShippingRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseShippingRefunded($maxBaseShippingRefunded)
    {
        $this->maxBaseShippingRefunded = $maxBaseShippingRefunded;

        return $this;
    }

    /**
     * Get maxBaseShippingRefunded
     *
     * @return float
     */
    public function getMaxBaseShippingRefunded()
    {
        return $this->maxBaseShippingRefunded;
    }

    /**
     * Set avgBaseShippingRefunded
     *
     * @param float $avgBaseShippingRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseShippingRefunded($avgBaseShippingRefunded)
    {
        $this->avgBaseShippingRefunded = $avgBaseShippingRefunded;

        return $this;
    }

    /**
     * Get avgBaseShippingRefunded
     *
     * @return float
     */
    public function getAvgBaseShippingRefunded()
    {
        return $this->avgBaseShippingRefunded;
    }

    /**
     * Set sumBaseShippingRefunded
     *
     * @param float $sumBaseShippingRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseShippingRefunded($sumBaseShippingRefunded)
    {
        $this->sumBaseShippingRefunded = $sumBaseShippingRefunded;

        return $this;
    }

    /**
     * Get sumBaseShippingRefunded
     *
     * @return float
     */
    public function getSumBaseShippingRefunded()
    {
        return $this->sumBaseShippingRefunded;
    }

    /**
     * Set minBaseTaxAmount
     *
     * @param float $minBaseTaxAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseTaxAmount($minBaseTaxAmount)
    {
        $this->minBaseTaxAmount = $minBaseTaxAmount;

        return $this;
    }

    /**
     * Get minBaseTaxAmount
     *
     * @return float
     */
    public function getMinBaseTaxAmount()
    {
        return $this->minBaseTaxAmount;
    }

    /**
     * Set maxBaseTaxAmount
     *
     * @param float $maxBaseTaxAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseTaxAmount($maxBaseTaxAmount)
    {
        $this->maxBaseTaxAmount = $maxBaseTaxAmount;

        return $this;
    }

    /**
     * Get maxBaseTaxAmount
     *
     * @return float
     */
    public function getMaxBaseTaxAmount()
    {
        return $this->maxBaseTaxAmount;
    }

    /**
     * Set avgBaseTaxAmount
     *
     * @param float $avgBaseTaxAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseTaxAmount($avgBaseTaxAmount)
    {
        $this->avgBaseTaxAmount = $avgBaseTaxAmount;

        return $this;
    }

    /**
     * Get avgBaseTaxAmount
     *
     * @return float
     */
    public function getAvgBaseTaxAmount()
    {
        return $this->avgBaseTaxAmount;
    }

    /**
     * Set sumBaseTaxAmount
     *
     * @param float $sumBaseTaxAmount
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseTaxAmount($sumBaseTaxAmount)
    {
        $this->sumBaseTaxAmount = $sumBaseTaxAmount;

        return $this;
    }

    /**
     * Get sumBaseTaxAmount
     *
     * @return float
     */
    public function getSumBaseTaxAmount()
    {
        return $this->sumBaseTaxAmount;
    }

    /**
     * Set minBaseTaxCanceled
     *
     * @param float $minBaseTaxCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseTaxCanceled($minBaseTaxCanceled)
    {
        $this->minBaseTaxCanceled = $minBaseTaxCanceled;

        return $this;
    }

    /**
     * Get minBaseTaxCanceled
     *
     * @return float
     */
    public function getMinBaseTaxCanceled()
    {
        return $this->minBaseTaxCanceled;
    }

    /**
     * Set maxBaseTaxCanceled
     *
     * @param float $maxBaseTaxCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseTaxCanceled($maxBaseTaxCanceled)
    {
        $this->maxBaseTaxCanceled = $maxBaseTaxCanceled;

        return $this;
    }

    /**
     * Get maxBaseTaxCanceled
     *
     * @return float
     */
    public function getMaxBaseTaxCanceled()
    {
        return $this->maxBaseTaxCanceled;
    }

    /**
     * Set avgBaseTaxCanceled
     *
     * @param float $avgBaseTaxCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseTaxCanceled($avgBaseTaxCanceled)
    {
        $this->avgBaseTaxCanceled = $avgBaseTaxCanceled;

        return $this;
    }

    /**
     * Get avgBaseTaxCanceled
     *
     * @return float
     */
    public function getAvgBaseTaxCanceled()
    {
        return $this->avgBaseTaxCanceled;
    }

    /**
     * Set sumBaseTaxCanceled
     *
     * @param float $sumBaseTaxCanceled
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseTaxCanceled($sumBaseTaxCanceled)
    {
        $this->sumBaseTaxCanceled = $sumBaseTaxCanceled;

        return $this;
    }

    /**
     * Get sumBaseTaxCanceled
     *
     * @return float
     */
    public function getSumBaseTaxCanceled()
    {
        return $this->sumBaseTaxCanceled;
    }

    /**
     * Set minBaseTaxInvoiced
     *
     * @param float $minBaseTaxInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseTaxInvoiced($minBaseTaxInvoiced)
    {
        $this->minBaseTaxInvoiced = $minBaseTaxInvoiced;

        return $this;
    }

    /**
     * Get minBaseTaxInvoiced
     *
     * @return float
     */
    public function getMinBaseTaxInvoiced()
    {
        return $this->minBaseTaxInvoiced;
    }

    /**
     * Set maxBaseTaxInvoiced
     *
     * @param float $maxBaseTaxInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseTaxInvoiced($maxBaseTaxInvoiced)
    {
        $this->maxBaseTaxInvoiced = $maxBaseTaxInvoiced;

        return $this;
    }

    /**
     * Get maxBaseTaxInvoiced
     *
     * @return float
     */
    public function getMaxBaseTaxInvoiced()
    {
        return $this->maxBaseTaxInvoiced;
    }

    /**
     * Set avgBaseTaxInvoiced
     *
     * @param float $avgBaseTaxInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseTaxInvoiced($avgBaseTaxInvoiced)
    {
        $this->avgBaseTaxInvoiced = $avgBaseTaxInvoiced;

        return $this;
    }

    /**
     * Get avgBaseTaxInvoiced
     *
     * @return float
     */
    public function getAvgBaseTaxInvoiced()
    {
        return $this->avgBaseTaxInvoiced;
    }

    /**
     * Set sumBaseTaxInvoiced
     *
     * @param float $sumBaseTaxInvoiced
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseTaxInvoiced($sumBaseTaxInvoiced)
    {
        $this->sumBaseTaxInvoiced = $sumBaseTaxInvoiced;

        return $this;
    }

    /**
     * Get sumBaseTaxInvoiced
     *
     * @return float
     */
    public function getSumBaseTaxInvoiced()
    {
        return $this->sumBaseTaxInvoiced;
    }

    /**
     * Set minBaseTaxRefunded
     *
     * @param float $minBaseTaxRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMinBaseTaxRefunded($minBaseTaxRefunded)
    {
        $this->minBaseTaxRefunded = $minBaseTaxRefunded;

        return $this;
    }

    /**
     * Get minBaseTaxRefunded
     *
     * @return float
     */
    public function getMinBaseTaxRefunded()
    {
        return $this->minBaseTaxRefunded;
    }

    /**
     * Set maxBaseTaxRefunded
     *
     * @param float $maxBaseTaxRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setMaxBaseTaxRefunded($maxBaseTaxRefunded)
    {
        $this->maxBaseTaxRefunded = $maxBaseTaxRefunded;

        return $this;
    }

    /**
     * Get maxBaseTaxRefunded
     *
     * @return float
     */
    public function getMaxBaseTaxRefunded()
    {
        return $this->maxBaseTaxRefunded;
    }

    /**
     * Set avgBaseTaxRefunded
     *
     * @param float $avgBaseTaxRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setAvgBaseTaxRefunded($avgBaseTaxRefunded)
    {
        $this->avgBaseTaxRefunded = $avgBaseTaxRefunded;

        return $this;
    }

    /**
     * Get avgBaseTaxRefunded
     *
     * @return float
     */
    public function getAvgBaseTaxRefunded()
    {
        return $this->avgBaseTaxRefunded;
    }

    /**
     * Set sumBaseTaxRefunded
     *
     * @param float $sumBaseTaxRefunded
     *
     * @return ReportMageOrderZipMonth
     */
    public function setSumBaseTaxRefunded($sumBaseTaxRefunded)
    {
        $this->sumBaseTaxRefunded = $sumBaseTaxRefunded;

        return $this;
    }

    /**
     * Get sumBaseTaxRefunded
     *
     * @return float
     */
    public function getSumBaseTaxRefunded()
    {
        return $this->sumBaseTaxRefunded;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
