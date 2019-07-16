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


	public $discipline;


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



	public $adultsAmount;

	public $kidsAmount;

	/**
	 * Adults + kids.
	 * @var int
	 */
	public $personAmount;



	public $blockStart;

	public $blockEnd;

	/**
	 * The ammount of time blocks that these classes will span.
	 * @var int
	 */
	public $timeBlocksAmt;




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
	 * Breakdown array of the price of the classes. Each element is the price of each student, and the sum of all is the classes price.
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
	 * The fees that the selected payment processor charges for this payment. They are added to classes price and conform the total.
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




	/**
	 * Surcharges of each additional student in case it's a group class.
	 * @var array
	 */
	private $groupSurcharges;





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


	/**
	 * Only function that should be called apart from constructor and calculate().
	 * @param string $discipline
	 * @param string $paymentMethod [description]
	 * @param Carbon\Carbon $date          [description]
	 * @param [type] $personAmmt    [description]
	 * @param [type] $blockStart    [description]
	 * @param [type] $blockEnd      [description]
	 * @return null
	 */
	public function set($discipline, $paymentMethod, $date, $adultsAmount, $kidsAmount, $blockStart, $blockEnd)
	{
		$this->discipline = $discipline;
		$this->setPaymentMethod($paymentMethod);
		$this->setServiceDate($date);
		$this->setPersonAmount($adultsAmount, $kidsAmount);
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
	 * @param int $adultsAmount
	 * @param int $kidsAmount
	 */
	public function setPersonAmount($adultsAmount, $kidsAmount)
	{
		$this->adultsAmount = $adultsAmount;
		$this->kidsAmount = $kidsAmount;
		$this->personAmount = $adultsAmount + $kidsAmount;
	}

	/**
	 * Set the length of these classes in ammount of time blocks.
	 * The important thing is the timeBlocksAmt, but start and end times should be compatible with instructor working times in the given date.
	 * @param int $ammount
	 */
	public function setBlocksSpan($blockStart, $blockEnd)
	{
		$this->blockStart = $blockStart;
		$this->blockEnd = $blockEnd;
		$this->timeBlocksAmt = ($blockEnd - $blockStart) + 1;
		
	}




	/**
	 * Calculates all the prices of the quote.
	 * Takes 1 DB query (on getPricePerBlockOnDate()).
	 */
	public function calculate()
	{
		$this->pricePerBlock = $this->service->getPricePerBlockOnDate($this->serviceDate);
		
		$this->groupSurcharges = $this->service->getGroupSurcharges();

		
		$studentBasePrice = $this->timeBlocksAmt * $this->pricePerBlock;

		for($i=1; $i <= $this->personAmount; $i++) {
			
			$totalStudent = $studentBasePrice * ($this->groupSurcharges[$i]/100);

			$this->classesPrice += $totalStudent;
			$this->classesPriceDetail[] = round($totalStudent, 2);
		}

		if($this->paymentMethod == PaymentMethods::CODE_MERCADOPAGO) {
			$this->payProviderFee = PaymentMethods::calculateMercadoPagoFees($this->classesPrice);
		}
		
		$this->total = $this->classesPrice + $this->payProviderFee;


		$this->serviceFee = $this->classesPrice * self::getSiteFeePercent()/100;
		$this->instructorPay = $this->classesPrice - $this->serviceFee;
	}



	/**
	 * Returns a table with the breakdown of the price in json format.
	 * Each column is a person, and the 1st column is the p
	 * @return string
	 */
	/*public function getJsonBreakdown()
	{
		$subtotalPerPerson = $this->timeBlocksAmt * $this->pricePerBlock;

		$table = [];

		for($i=1; $i <= $this->personAmount; $i++) {	
			$row = [
				round($subtotalPerPerson, 2),
				round($subtotalPerPerson * $this->groupDiscounts[$i]/100, 2),
				round($subtotalPerPerson * (1 - $this->groupDiscounts[$i]/100), 2)
			];
			$table[] = $row;
		}

		return json_encode($table);
	}*/


}