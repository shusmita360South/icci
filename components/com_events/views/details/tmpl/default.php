<?php
# no direct access
defined('_JEXEC') or die;
$doc  = JFactory::getDocument();
$doc->addScript('https://addevent.com/libs/atc/1.6.1/atc.min.js');
#echo '<pre>'; print_r($this->items); echo '</pre>';
$item = $this->items;
$item = $item[0];
$itemid = JRequest::getVar('Itemid');


//render breadcrumb module
$modName = 'mod_breadcrumbs'; 
$modTitle = 'Breadcrumbs';
$_mod_breadcrumb = JModuleHelper::getModule($modName, $modTitle);
$mod_breadcrumb = JModuleHelper::renderModule($_mod_breadcrumb);

//related events
if(isset($item->relatedevents) ) {
  $relatedEvents = EventsHelper::getRelatedEvents($item->relatedevents );
}
?>
<div class="page-header-1 light-bg" style="background-image: url(<?php echo  JURI::base().$item->image;?>)">
  <div class="grid-container-medium">
    <p><a href="/events/upcoming-events/"><span uk-icon="arrow-left"></span> Back to Events</a></p>
    <h1><?php echo $item->title;?></h1>
    <h4><?php echo $item->subtitle;?></h4>
  </div>
</div>
<div class="events-detail section-padding-tb">
  <div class="grid-container-medium">
    <div uk-grid>
      <div class="uk-width-1-1 uk-width-2-3@s">
        <div class="content-left">
          <h2>Description</h2>
          <div class="article-default">
            <?php echo $item->body;?>
          </div>
          <?php if ($item->faq):?>
            <?php $itemFAQs = json_decode($item->faq);
              $itemQs = $itemFAQs->question;
              $itemAs = $itemFAQs->answer;
            ?>
            <h2 class="uk-margin-medium-top">FAQs</h2>
            <ul uk-accordion>
                <?php foreach ($itemQs as $key=>$itemFAQ) : ?>
                    <li class="accordion-list <?php echo $key==0 ? 'uk-open':''?>">
                        <a class="uk-accordion-title" href="#"><?php echo $itemFAQ; ?></a>
                        <div class="uk-accordion-content">
                            <p>
                              <?php echo preg_replace('/(<[^>]+) style=".*?"/i','$1',$itemAs[$key]); ?>
                            </p>
                        </div>
                    </li>  
                <?php endforeach; ?>
            </ul>       
          <?php endif;?>
          <?php if ($item->host):?>
            <?php $itemHosts = json_decode($item->host);
              $itemHosts = $itemHosts->image;
            ?>
            <h2 class="uk-margin-medium-top">Hosted by</h2>
            <div uk-grid class="uk-flex-left">
            <?php foreach ($itemHosts as $key => $itemHost) :?>
              <div class="banneritem uk-width-1-3 uk-width-1-4@s uk-width-auto@m">
                <img src="<?php echo $itemHost;?>"/>
              </div>
            <?php endforeach;?>
            </div>
          <?php endif;?>
          <?php if ($item->partners):?>
            <?php $itemPartners = json_decode($item->partners);
              $itemPartners = $itemPartners->imagep;
            ?>
            <h2 class="uk-margin-medium-top">Event Partners</h2>
            <div uk-grid class="uk-flex-left">
            <?php foreach ($itemPartners as $key => $itemPartner) :?>
              <div class="banneritem uk-width-1-3 uk-width-1-4@s uk-width-auto@m">
                <img src="<?php echo $itemPartner;?>"/>
              </div>
            <?php endforeach;?>
            </div>
          <?php endif;?>
        </div>
      </div>
      <div class="uk-width-1-1 uk-width-1-3@s">
        <div class="content-right">
          <h3>Event Info</h3>
          <?php
            $sdate=date_create($item->stime);
            $ddate = date_format($sdate,"l jS F Y");

            $edate=date_create($item->etime);

          ?> 
          <div class="card-1 article-default">
            <p>
              <span class="icon icon-calender"></span> <?php echo $ddate;?><br/>
              <span class="icon icon-time"></span> <?php echo date_format($sdate,"H:i")." - ".date_format($edate,"H:i");?><br/>
              <span class="icon icon-location"></span> <strong><?php echo $item->locationtitle;?></strong><br/><span class="location"><?php echo $item->location;?></span>
              <div title="Add to Calendar" class="addeventatc">
                  Add to Calendar
                  <span class="start"><?php echo date_format($sdate,"d-m-Y H:i:s");?></span>
                  <span class="end"><?php echo date_format($edate,"d-m-Y H:i:s");?></span>
                  <span class="timezone">Australia/Victoria</span>
                  <span class="title"><?php echo $item->title;?></span>
                  <span class="description"><?php echo $item->body;?></span>
                  <span class="location"><?php echo $item->locationtitle;?><br><?php echo $item->location;?></span>
              </div>
            </p>
          </div>
          <h3 class="uk-margin-medium-top">Costs</h3>
          <div class="card-1">
            <p>
              <span class="icon icon-member"></span> <strong><?php echo $item->memberfee;?> ICCI Member fee</strong>
            </p>
            <p>
              <span class="icon icon-nonmember"></span> <strong><?php echo $item->nonmemberfee;?> Non-member fee</strong>
            </p>
            <a href="<?php echo $item->link;?> " class="button btn-blue uk-margin-small-top">Book Now</a>
          </div>
          <h3 class="uk-margin-medium-top">Event Location</h3>
          <div class="card-map">
           
            <iframe
              width="340"
              height="220"
              frameborder="0" style="border:0"
              src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAvavqTeZXc48iALmnGfypI6ZuJ1jNW-lE
                &q=<?php echo $item->location;?>" allowfullscreen>
            </iframe>
          </div>
          <h3 class="uk-margin-medium-top">Share with friends</h3>
          <div class="card-share">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo JUri::current();?>"><span class="icon-outer"><span uk-icon="icon: facebook"></span></span></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo JUri::current();?>&title=<?php echo $item->title ?>"><span class="icon-outer"><span uk-icon="icon: linkedin"></span></span></a>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if($item->relatedevents):?>
<div class="events-related section-padding-tb light-bg">
  <div class="grid-container">
    <h2 class="center">Other Events you may like</h2>
    <div uk-grid class="uk-margin-medium-top">
      <?php foreach ($relatedEvents as $relatedEvent): ?>
    
        <?php
          $linky = JURI::base().substr(JRoute::_('index.php?option=com_projects&view=details&id='.$relatedEvent->id.':'.JFilterOutput::stringURLSafe($relatedEvent->title).'&Itemid='.$itemid),strlen(JURI::base(true))+1); 
          //$cats = EventsHelper::getCategories($relatedEvent->id);
            $sdate=date_create($relatedEvent->stime);
            $ddate = date_format($sdate,"l jS F Y");

            $edate=date_create($relatedEvent->etime);
       
        ?>
      
        <div class="card-event uk-width-1-1 uk-width-1-4@s<?php //foreach ($cats as  $cat) {echo " ".str_replace(' ','-',strtolower($cat[0]));}?>">
            <a href="<?php echo $linky;?>">
              <div class="item ">
                  <div class="image">
                    <img src="<?php echo $relatedEvent->thumb;?>"/>     
                  </div>
                  <div class="content white-bg">
                    <div class="content-date">
                      <p class="month"><?php echo date_format($sdate,"M");?></p>
                      <p class="day"><?php echo date_format($sdate,"j");?></p>
                    </div>
                    <div class="content-content">
                      <h6><?php echo $relatedEvent->title; ?></h6>
                      <p class="time"><?php echo date_format($sdate,"H:i")." - ".date_format($edate,"H:i");?></p>
                      <p><?php echo $relatedEvent->intro; ?></p>
                      <p class="readmore">Read More</p>
                    </div>
                  </div>
  
              </div>
            </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif;?>



