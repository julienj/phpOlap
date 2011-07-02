<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Layout;

use phpOlap\Metadata\ResultSetInterface;

/**
*	Layout Interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Layout
*/

interface LayoutInterface {
	
    /**
     * Constructor.
     *
     * @param ResultSetInterface $resultSet The resultSet object
     *
     */	
	public function __construct (ResultSetInterface $resultSet);
	

    /**
     * generate the layout
     *
     * @return String Layout
     *
     */
	public function generate();

}