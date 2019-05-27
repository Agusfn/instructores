<?php
namespace App;

use App\InstructorService;
use App\Lib\PaymentMethods;

class Quote
{


	/**
	 * @var App\InstructorService
	 */
	private $service;



	/**
	 * Payment method code. List available at App\Lib\PaymentMethods.
	 * @var string
	 */
	public $paymentMethod;


	/**
	 * The date in which the service/classes will be provided.
	 * @var Carbon\Carbon
	 */
	public $serviceDate;


	/**
	 * Ammount of persons that will take these classes.
	 * @var int
	 */
	public $personAmmount;


	public $blockStart;

	public $blockEnd;

	/**
	 * The ammount of time blocks that these classes will span.
	 * @var int
	 */
	public $timeBlocksAmmt;




	/**
	 * Currency in which this quote is calculated. Depends on the payment processor.
	 * Currencies available at App\Lib\Currencies
	 * @var string
	 */
	public $currency;

	/**
	 * The price per time block of the instructor on the given date.
	 * @var float
	 */
	public $pricePerBlock;



	/**
	 * The ammount that constitute the price of the classes/service.
	 * It gets calculated multiplying the price per hour of the instructor by the ammt of hours, it takes into account group classes and group discounts.
	 * @var float
	 */
	public $classesPrice = 0;


	/**
	 * An array with a breakdown of the price of the classes. It is a sum of the price for each person (if group), taking into account the price per block,
	 * the discount for groups, and the total time blocks.
	 * @var array
	 */
	public $classesPriceDetail;



	/**
	 * The fees that this site takes out from classesPrice.
	 * @var float
	 */
	public $serviceFee;


	/**
	 * The ammount that the instructor is going to recieve from a sale of this quote.
	 * instructorPay = classesPrice - serviceFee
	 * @var float
	 */
	public $instructorPay;


	/**
	 * The fees that the selected payment processor charges for this payment.
	 * @var float
	 */
	public $payProviderFee = 0;


	/**
	 * Total ammount to be charged to customer.
	 * total = serviceFee + instructorPay + payProviderFee
	 * total = classesPrice + payProviderFee
	 * @var float
	 */
	public $total;



	public function __construct(InstructorService $service)
	{
		$this->service = $service;
	}



	/**
	 * Get the percentage that is charged to the instructor's classes price
	 * @return float
	 */
	public static function getSiteFeePercent()
	{
		return \Setting::get("prices.service_fee");
	}

	/**
	 * Set the percentage that is charged to the instructor's classes price
	 * @param float $percentage
	 */
	public static function setSiteFeePercent($percentage)
	{
		\Setting::set("prices.service_fee", $percentage);
		\Setting::save();
	}


	public function set($paymentMethod, $date, $personAmmt, $blockStart, $blockEnd)
	{
		$this->setPaymentMethod($paymentMethod);
		$this->setServiceDate($date);
		$this->setPersonAmmount($personAmmt);
		$this->setBlocksSpan($blockStart, $blockEnd);
	}


	/**
	 * Set the payment method that will be used. List available at App\Lib\PaymentMethods.
	 * @param string $method
	 */
	public function setPaymentMethod($method)
	{
		$this->paymentMethod = $method;
		$this->currency = PaymentMethods::$currencies[$method];
	}

	/**
	 * Set date of when the service/classes will be provided.
	 * Must be compatible with instructor's date availability.
	 * @param Carbon\Carbon $date
	 */
	public function setServiceDate($date)
	{
		$this->serviceDate = $date;
	}

	/**
	 * Set the ammount of persons that will take these classes.
	 * Must me compatible with instructor group classes policy.
	 * @param int $ammount
	 */
	public function setPersonAmmount($ammount)
	{
		$this->personAmmount = $ammount;
	}

	/**
	 * Set the length of these classes in ammount of time blocks.
	 * Must be compatible with instructor working times in the given date, otherwise it's nonsense.
	 * @param int $ammount
	 */
	public function setBlocksSpan($blockStart, $blockEnd)
	{
		$this->blockStart = $blockStart;
		$this->blockEnd = $blockEnd;
		$this->timeBlocksAmmt = ($blockEnd - $blockStart) + 1;
		
	}




	/**
	 * Calculates all the prices of the quote.
	 */
	public function calculate()
	{
		$this->pricePerBlock = $this->service->getPricePerBlockOnDate($this->serviceDate);
		$groupDiscounts = $this->service->getGroupDiscounts();

		$subtotalPerPerson = $this->timeBlocksAmmt * $this->pricePerBlock;

		for($i=1; $i <= $this->personAmmount; $i++) {

			$totalPerson = $subtotalPerPerson * (1 - $groupDiscounts[$i]/100);
			$this->classesPrice += $totalPerson;

			$this->classesPriceDetail[] = [
				round($subtotalPerPerson, 2),
				$groupDiscounts[$i],
				round($totalPerson, 2)
			];

		}


		$this->serviceFee = $this->classesPrice * self::getSiteFeePercent()/100;

		$this->instructorPay = $this->classesPrice - $this->serviceFee;

		if($this->paymentMethod == PaymentMethods::MERCADOPAGO) {
			$this->payProviderFee = PaymentMethods::calculateMercadoPagoFees($this->classesPrice);
		}
		

		$this->total = $this->classesPrice + $this->payProviderFee;


	}




}