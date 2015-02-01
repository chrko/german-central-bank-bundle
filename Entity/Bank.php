<?php

namespace ChrKo\Bundle\GermanCentralBankBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bank
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ChrKo\Bundle\GermanCentralBankBundle\Entity\BankRepository")
 */
class Bank
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=58)
     *
     * @Assert\Length(min="3", max="58")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=27)
     *
     * @Assert\Length(max="27")
     * @Assert\NotBlank()
     */
    private $shortname;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=5)
     *
     * @Assert\Length(min="4", max="5")
     * @Assert\NotBlank()
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=35)
     *
     * @Assert\Length(max="35")
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_number", type="string", length=8)
     *
     * @Assert\Length(min="8", max="8")
     * @Assert\NotBlank()
     */
    private $bankNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="bic", type="string", length=11)
     *
     * @Assert\Length(min="11", max="11")
     */
    private $bic;

    /**
     * @var string
     *
     * @ORM\Column(name="check_digit_plan_calculation_method", type="string", length=2)
     *
     * @Assert\Length(max="2")
     * @Assert\NotBlank()
     */
    private $checkDigitPlanCalculationMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="attribute", type="string", length=1)
     *
     * @Assert\NotBlank()
     */
    private $attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="pan", type="string", length=5)
     *
     * @Assert\Length(min="5", max="5")
     */
    private $pan;

    /**
     * @var string
     *
     * @ORM\Column(name="record_number", type="string", length=6)
     *
     * @Assert\Length(max="6")
     * @Assert\NotBlank()
     */
    private $recordNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="modification_identifier", type="string", length=1)
     *
     * @Assert\Choice(choices={"A", "D", "M", "U"})
     * @Assert\NotBlank()
     */
    private $modificationIdentifier;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bank_number_deletion", type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $bankNumberDeletion;

    /**
     * @var string
     *
     * @ORM\Column(name="successor_bank_number", type="string", length=8)
     *
     * @Assert\Length(min="8", max="8")
     * @Assert\NotBlank()
     */
    private $successorBankNumber = '00000000';

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Bank
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortname
     *
     * @param string $shortname
     *
     * @return Bank
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Get shortname
     *
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return Bank
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Bank
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set bankNumber
     *
     * @param string $bankNumber
     *
     * @return Bank
     */
    public function setBankNumber($bankNumber)
    {
        $this->bankNumber = $bankNumber;

        return $this;
    }

    /**
     * Get bankNumber
     *
     * @return string
     */
    public function getBankNumber()
    {
        return $this->bankNumber;
    }

    /**
     * Set bic
     *
     * @param string $bic
     *
     * @return Bank
     */
    public function setBic($bic)
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * Get bic
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Set checkDigitPlanCalculationMethod
     *
     * @param string $checkDigitPlanCalculationMethod
     *
     * @return Bank
     */
    public function setCheckDigitPlanCalculationMethod($checkDigitPlanCalculationMethod)
    {
        $this->checkDigitPlanCalculationMethod = $checkDigitPlanCalculationMethod;

        return $this;
    }

    /**
     * Get checkDigitPlanCalculationMethod
     *
     * @return string
     */
    public function getCheckDigitPlanCalculationMethod()
    {
        return $this->checkDigitPlanCalculationMethod;
    }

    /**
     * Set attribute
     *
     * @param string $attribute
     *
     * @return Bank
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set pan
     *
     * @param string $pan
     *
     * @return Bank
     */
    public function setPan($pan)
    {
        $this->pan = $pan;

        return $this;
    }

    /**
     * Get pan
     *
     * @return string
     */
    public function getPan()
    {
        return $this->pan;
    }

    /**
     * Set recordNumber
     *
     * @param string $recordNumber
     *
     * @return Bank
     */
    public function setRecordNumber($recordNumber)
    {
        $this->recordNumber = $recordNumber;

        return $this;
    }

    /**
     * Get recordNumber
     *
     * @return string
     */
    public function getRecordNumber()
    {
        return $this->recordNumber;
    }

    /**
     * Set modificationIdentifier
     *
     * @param string $modificationIdentifier
     *
     * @return Bank
     */
    public function setModificationIdentifier($modificationIdentifier)
    {
        $this->modificationIdentifier = $modificationIdentifier;

        return $this;
    }

    /**
     * Get modificationIdentifier
     *
     * @return string
     */
    public function getModificationIdentifier()
    {
        return $this->modificationIdentifier;
    }

    /**
     * Set bankNumberDeletion
     *
     * @param boolean $bankNumberDeletion
     *
     * @return Bank
     */
    public function setBankNumberDeletion($bankNumberDeletion)
    {
        if (is_string($bankNumberDeletion)) {
            $bankNumberDeletion = $bankNumberDeletion == '!';
        }
        $this->bankNumberDeletion = $bankNumberDeletion;

        return $this;
    }

    /**
     * Get bankNumberDeletion
     *
     * @return boolean
     */
    public function getBankNumberDeletion()
    {
        return $this->bankNumberDeletion;
    }

    /**
     * Set successorBankNumber
     *
     * @param string $successorBankNumber
     *
     * @return Bank
     */
    public function setSuccessorBankNumber($successorBankNumber)
    {
        $this->successorBankNumber = $successorBankNumber;

        return $this;
    }

    /**
     * Get successorBankNumber
     *
     * @return string
     */
    public function getSuccessorBankNumber()
    {
        return $this->successorBankNumber;
    }
}
