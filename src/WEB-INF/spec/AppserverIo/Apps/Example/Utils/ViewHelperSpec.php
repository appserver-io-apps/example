<?php

namespace spec\AppserverIo\Apps\Example\Utils;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ViewHelperSpec extends ObjectBehavior
{

    function it_returns_the_logout_link()
    {
        $this->beConstructedThrough('singleton');
        $this->getLogoutLink()->shouldReturn('index.do/logout');
    }
}
