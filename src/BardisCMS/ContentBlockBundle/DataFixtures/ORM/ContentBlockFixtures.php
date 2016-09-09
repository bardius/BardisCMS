<?php

/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\ContentBlockBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\ContentBlockBundle\Entity\ContentBlock;

class ContentBlockFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $contentSampleHome = new ContentBlock();
        $contentSampleHome->setTitle('Sample Content Home');
        $contentSampleHome->setPublishedState(1);
        $contentSampleHome->setAvailability('page');
        $contentSampleHome->setShowTitle(1);
        $contentSampleHome->setOrdering(1);
        $contentSampleHome->setSizeClass('large-12');
        $contentSampleHome->setContentType('html');
        $contentSampleHome->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
        $manager->persist($contentSampleHome);

        $contentSample1 = new ContentBlock();
        $contentSample1->setTitle('Sample Content 1');
        $contentSample1->setPublishedState(1);
        $contentSample1->setAvailability('page');
        $contentSample1->setShowTitle(1);
        $contentSample1->setOrdering(1);
        $contentSample1->setClassName('sampleClassname');
        $contentSample1->setSizeClass('large-12');
        $contentSample1->setIdName('sampleId');
        $contentSample1->setContentType('html');
        $contentSample1->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
        $manager->persist($contentSample1);

        $contentSample2 = new ContentBlock();
        $contentSample2->setTitle('Sample Content 2');
        $contentSample2->setPublishedState(1);
        $contentSample2->setAvailability('page');
        $contentSample2->setShowTitle(1);
        $contentSample2->setOrdering(2);
        $contentSample2->setSizeClass('large-12');
        $contentSample2->setContentType('html');
        $contentSample2->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
        $manager->persist($contentSample2);

        $contentSampleContact = new ContentBlock();
        $contentSampleContact->setTitle('Sample Contact Form');
        $contentSampleContact->setPublishedState(1);
        $contentSampleContact->setAvailability('page');
        $contentSampleContact->setShowTitle(1);
        $contentSampleContact->setOrdering(1);
        $contentSampleContact->setSizeClass('large-12');
        $contentSampleContact->setContentType('contact');
        $manager->persist($contentSampleContact);

        $contentSampleBlog1 = new ContentBlock();
        $contentSampleBlog1->setTitle('Sample Blog Content 1');
        $contentSampleBlog1->setPublishedState(1);
        $contentSampleBlog1->setAvailability('page');
        $contentSampleBlog1->setShowTitle(1);
        $contentSampleBlog1->setOrdering(1);
        $contentSampleBlog1->setClassName('sampleClassname');
        $contentSampleBlog1->setSizeClass('large-12');
        $contentSampleBlog1->setIdName('sampleId');
        $contentSampleBlog1->setContentType('html');
        $contentSampleBlog1->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
        $manager->persist($contentSampleBlog1);

        $contentHomeSlide1 = new ContentBlock();
        $contentHomeSlide1->setTitle('Home Top Banner Slide 1');
        $contentHomeSlide1->setPublishedState(1);
        $contentHomeSlide1->setAvailability('page');
        $contentHomeSlide1->setShowTitle(0);
        $contentHomeSlide1->setOrdering(1);
        $contentHomeSlide1->setSizeClass('large-12');
        $contentHomeSlide1->setContentType('slide');
        $contentHomeSlide1->setSlide($manager->merge($this->getReference('homeSlide1')));
        $manager->persist($contentHomeSlide1);

        $contentHomeSlide2 = new ContentBlock();
        $contentHomeSlide2->setTitle('Home Top Banner Slide 2');
        $contentHomeSlide2->setPublishedState(1);
        $contentHomeSlide2->setAvailability('page');
        $contentHomeSlide2->setShowTitle(0);
        $contentHomeSlide2->setOrdering(2);
        $contentHomeSlide2->setSizeClass('large-12');
        $contentHomeSlide2->setContentType('slide');
        $contentHomeSlide2->setSlide($manager->merge($this->getReference('homeSlide2')));
        $manager->persist($contentHomeSlide2);

        $contentTnc = new ContentBlock();
        $contentTnc->setTitle('Privacy Policy');
        $contentTnc->setPublishedState(1);
        $contentTnc->setAvailability('page');
        $contentTnc->setShowTitle(1);
        $contentTnc->setOrdering(1);
        $contentTnc->setSizeClass('large-12');
        $contentTnc->setContentType('html');
        $contentTnc->setHtmlText('<h4>Changes to this privacy statement or use of data</h4>
<p>From time to time, we may add or change functions, features or services offered by advertisement online or offline and at our website/s. This, and our commitment to protecting the privacy of your personal information, may result in periodic changes to this Privacy Policy.  As a result, please remember to refer back to this Privacy Policy regularly to review any amendments. If at any point we decide to use personally identifiable information in a manner different from that stated at the time it was collected, we will notify users by way of an email or SMS text. We will use information in accordance with the privacy policy under which the information was collected.  </p>
<h4>Your consent and acceptance of these Privacy Statement terms</h4>
<p>By using any of our websites or our Services, you unconditionally agree to be bound by this Privacy Policy.</p>
<h4>Data collection and usage</h4>
<p>We collect and processes information from your website visits, requests for services and phone calls in order to:</p>
<ol>
<li>Identify you each time you visit a website or wish to have a Service provided;</li>
<li>Process and send requests by you, such as email and text updates</li>
<li>Improve our Services and Website.</li>
<li>Customise the information you receive from us via registration process, and which support any specific requests for information you may make through keyword searches;</li>
<li>Send you information we think you may find useful, including information about new and existing services, offers and newsletters.</li>
<li>Compile customer testimonials that may be repeated on advertising material online or offline but without mention of your email or contact details.</li>
</ol>
<h4>About registering with us for services</h4>
<p>In order to use some of our website services such as email and SMS/text updates, you must first complete the registration form online at our website or register with us over the telephone. During registration you are required to give their contact information (such as name and email address). This information is used to send you the information you requested about our services at the time of registration, as well as future information about those same services and others that could be of interest in the future.</p>
<p>You are under no obligation to provide all information or receive other company marketing communications, but if you choose not to provide certain details please be ware that that this may mean we are unable to provide you with certain services or to personalise/tailor our services for you (for example our email and text Property Alert updates, and our property market newsletter).</p>
<h4>About our email and SMS text service, user name and password</h4>
<p>If you sign up to use our email and or SMS text property alert update services, or any other marketing communications, post customer registration, we send you a welcoming email and or SMS/text to verify password and username. Registered customers will receive information on our services, products, offers, and a newsletter at the beginning, during and after service provision.</p>
<h4>Correction/Updating/Unsubscribe facility to our website and email / SMS text services</h4>
<p>All of our emails and SMS text offer the facility for you to opt-in or opt-out/unsubscribe at any time. Or your can visit our website using your allocated user name / email address and password to access and update your preferences. You can stop our SMS / Text Messing service anytime by replying with the words `STOP`</p>
<h4>Suggestion and service request forms</h4>
<p>In addition to registration, our website offers form facilities for feedback or request of service such as financial services. Information requested may include contact information (such as name and telephone numbers), and demographic information (such as postcode and age level). Survey information will be used for the offer and provision of services and to monitor and improve the use and satisfaction of the website and services.</p>
<h4>Email-A-Friend and Recommendation scheme</h4>
<p>We provide a facility for property details or other information at our website to be forwarded to a friend. You may also elect to use our referral service for informing a friend about our services (for example our recommendation scheme). We ask you for your friend`s name and email address so that we can contact the friend by phone, email, SMS text or post inviting them to consider our services. The friend may contact us to request we unsubscribe their data from our marketing communications.</p>
<h4>Security</h4>
<p>We place a great importance on the security of all information associated with our customers. We have security measures in place to attempt to protect against the loss, misuse and alteration of customer data under our control. For example, our security and privacy policies are periodically reviewed and enhanced as necessary and only authorised personnel have access to user information. With regard to our websites, we use secure server software (SSL) to encrypt financial information you input before it is sent to us. While we cannot ensure or guarantee that loss, misuse or alteration of data will not occur, we use our best efforts to prevent this.</p>
<h4>Sharing</h4>
<p>We may share aggregated demographic information with our partners and advertisers. This is not linked to any personal information that can identify any individual person.  We may use an outside credit card processing company for payment of services. These companies do not retain, share, store or use personally identifiable information for any secondary purposes. We partner with another party to provide specific services e.g. Property Management services. When a customer signs up for these services, we will share names, or other contact information that is necessary for the third party to provide these services. These parties are not allowed to use personally identifiable information except for the purpose of providing these services.</p>
<h4>Supplementation of Information / credit checks </h4>
<p>In order for this website to properly fulfil its obligation to our customers, it is necessary for us to supplement the information we receive with information from 3rd party sources.  For example, to determine if tenant customers have satisfactory references we will use credit reference data via third party service.</p>
<h4>Site and Service Updates</h4>
<p>We also send the user site and service announcement updates. Members are not able to un-subscribe from service announcements, which contain important information about the service. We communicate with the user to provide requested services and in regards to issues relating to their account via email or phone.</p>
<h4>Log files/IP addresses </h4>
<p>When you visit our website, we may automatically log your IP address (the unique address which identifies your computer on the internet), which is automatically recognised by our web server. We use IP addresses to help us administer our Website and may collect broad demographic information for aggregate use. We do not link IP addresses to personally identifiable information.</p>
<p><strong>Non-personal information</strong><br>We may automatically collect non-personal information about you such as the type of Internet browsers you use or the site from which you linked to our websites. You cannot be identified from this information and it is only used to assist us in providing an effective service on our Website. We may from time to time supply the owners or operators of third party sites from which it is possible to link to our Website with information relating to the number of users linking to our Website from their sites. You cannot be identified from this information.</p>
<h4 id="cookies">Use of Cookies</h4>
<p>Cookies are pieces of information that a website transfers to your hard drive to store and sometimes track information about you. Most web browsers automatically accept cookies, but if you prefer, you can change your browser to prevent that.  However, you may not be able to take full advantage of a website if you do so. Cookies are specific to the server that created them and cannot be accessed by other servers, which means they cannot be used to track your movements around the web. Although they do identify a user`s computer, cookies do not personally identify customers or passwords. Credit card information is not stored in cookies.</p>
<p>We use cookies for the following reasons:</p>
<ol>
<li>To identify who you are and to access your account information;</li>
<li>To estimate our audience size and patterns;</li>
<li>To ensure that you are not asked to register twice;</li>
<li>To track preferences and to improve and update our website; and</li>
<li>To track the progress and number of emails opened in some of our marketing communications.</li>
</ol>
<h4>Shared information</h4>
<p>We will not share, sell or rent your personal information to third parties. However, we may disclose your personal information to third party suppliers who provide services on our behalf, for instance financial services providers.</p>
<p>We may disclose aggregate statistics about our sales, our website visitors and customers of our telephone services in order to describe our services to prospective partners, advertisers and other reputable third parties and for other lawful purposes, but these statistics will not include personally identifying information.</p>
<p>We may disclose personal information if required to do so by law or if it believes that such action is necessary to protect and defend the rights, property or personal safety of us and our website, visitors to the website and customers of our Services</p>
<p>We will only sell or rent your personal information to a third party either as part of a sale of the assets of a our company or having ensured that steps have been taken to ensure that your privacy rights continue to be protected.</p>
<h4>Links</h4>
<p>This website contains links to other sites. Please be aware that we are not responsible for the privacy practices of such other sites. We encourage our users to be aware when they leave our site and to read the privacy statements of each and every website that collects personally identifiable information. This privacy statement applies solely to information collected by this website.</p>
<h4>Information storage</h4>
<p>Information, which you submit via our website or our telephone services, is stored on a computer located in the European Economic Area. This is necessary in order to process the information and to send you any information you have requested. We may transfer information submitted by you to our other offices and to reputable third party suppliers, which may be situated outside the European Economic Area. Not all countries outside the EEA have data protection or privacy laws. In addition, if you use our Services while you are outside the EEA, your information may be transferred outside the EEA in order to provide you with those Services.</p>
');
        $manager->persist($contentTnc);

        $manager->flush();

        $this->addReference('contentSampleHome', $contentSampleHome);
        $this->addReference('contentSample1', $contentSample1);
        $this->addReference('contentSample2', $contentSample2);
        $this->addReference('contentSampleContact', $contentSampleContact);
        $this->addReference('contentSampleBlog1', $contentSampleBlog1);
        $this->addReference('contentHomeSlide1', $contentHomeSlide1);
        $this->addReference('contentHomeSlide2', $contentHomeSlide2);
        $this->addReference('contentTnc', $contentTnc);
    }

    public function getOrder() {
        return 8;
    }

}
