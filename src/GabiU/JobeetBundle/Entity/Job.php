<?php

namespace GabiU\JobeetBundle\Entity;

use GabiU\JobeetBundle\Model\JobInterface;
use GabiU\JobeetBundle\Model\MailerObjectInterface;
use GabiU\JobeetBundle\Utils\Jobeet as Jobeet;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation\MaxDepth;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Job
 * @Vich\Uploadable
 * @ExclusionPolicy("none")
 */
class Job implements JobInterface, MailerObjectInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     *
     */
    protected $logo;

    /**
     * @Vich\UploadableField(mapping="companyLogo", fileNameProperty="logo")
     * @var File $logoFile
     */
    protected $logoFile;

    /**
     * @return File|UploadedFile
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @param File|UploadedFile $logoFile
     */
    public function setLogoFile($logoFile = null)
    {
        $this->logoFile = $logoFile;

        if ($logoFile)
        {
            $this->setUpdatedAtValue();
        }
    }
    /**
     * @var string
     * @Expose()
     */
    private $url;

    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     * @Expose()
     */
    private $description;

    /**
     * @var string
     */
    private $howToApply;

    /**
     * @var string
     */
    private $token;

    /**
     * @var boolean
     */
    private $isPublic;

    /**
     * @var boolean
     */
    private $isActivated = false;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $expiresAt;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


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
     * Set type
     *
     * @param string $type
     * @return Job
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Job
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Job
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Job
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Job
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Job
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set howToApply
     *
     * @param string $howToApply
     * @return Job
     */
    public function setHowToApply($howToApply)
    {
        $this->howToApply = $howToApply;

        return $this;
    }

    /**
     * Get howToApply
     *
     * @return string 
     */
    public function getHowToApply()
    {
        return $this->howToApply;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Job
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return Job
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set isActivated
     *
     * @param boolean $isActivated
     * @return Job
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * Get isActivated
     *
     * @return boolean 
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Job
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return Job
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Job
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Job
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @var \GabiU\JobeetBundle\Entity\Category
     * @MaxDepth(0)
     * @Exclude()
     */
    private $category;


    /**
     * Set category
     *
     * @param \GabiU\JobeetBundle\Entity\Category $category
     * @return Job
     */
    public function setCategory(\GabiU\JobeetBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \GabiU\JobeetBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if (!$this->getCreatedAt())
        {
            $this->setCreatedAt(new \DateTime());
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function getCompanySlug()
    {
        return Jobeet::slugify($this->getCompany());
    }

    public function getPositionSlug()
    {
        return Jobeet::slugify($this->getPosition());
    }

    public function getLocationSlug()
    {
        return Jobeet::slugify($this->getLocation());
    }

    /**
     * @ORM\PrePersist
     */
    public function setExpiresAtValue()
    {
        if(!$this->getExpiresAt())
        {
            $now = $this->getCreatedAt() ? $this->getCreatedAt()->format("U") : time();
            $this->setExpiresAt(new \DateTime(date("Y-m-d H:i:s", $now + 86400 * 30)));
        }
    }

    /**
     * Extends this current job with 30 days
     * @return $this
     */
    public function extend()
    {
        $this->setExpiresAt(new \DateTime($this->getExpiresAt()->add(new \DateInterval("P30D"))->format("Y-m-d H:i:s")));
        return $this;
    }

    public static function getTypes()
    {
        return array(
            "full-time" => "Full-Time",
            "part-time" => "Part-Time",
            "freelance" => "Freelance"
        );
    }

    public static function getTypeValues(){
        return array_keys(self::getTypes());
    }

    /**
     * @ORM\PrePersist
     */
    public function setTokenValue()
    {
        if(!$this->getToken())
        {
            $this->setToken(sha1($this->getEmail().rand(111111, 999999)));
        }
    }

    public function isExpired()
    {
        return $this->getDaysBeforeExpires() < 0;
    }

    public function expiresSoon()
    {
        return $this->getDaysBeforeExpires() < 5;
    }

    public function getDaysBeforeExpires()
    {
        return ceil(($this->getExpiresAt()->format("U") - time()) / 86400);
    }

    public function __toString()
    {
        return sprintf("#%s - %s (%s in %s)", $this->getId(), $this->getPosition(), $this->getCompany(), $this->getLocation());
    }
}
