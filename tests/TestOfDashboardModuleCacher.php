<?php
/**
 *
 * ThinkUp/tests/TestOfDashboardModuleCacher.php
 *
 * Copyright (c) 2012-2015 Gina Trapani
 *
 * LICENSE:
 *
 * This file is part of ThinkUp (http://thinkup.com).
 *
 * ThinkUp is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThinkUp is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Test Of Installer
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2012-2015 Gina Trapani
 * @author Gina Trapani <ginatrapani[at]gmail[dot]com>
 */
require_once dirname(__FILE__).'/init.tests.php';
require_once THINKUP_WEBAPP_PATH.'_lib/extlib/simpletest/autorun.php';
require_once THINKUP_WEBAPP_PATH.'config.inc.php';

class TestOfDashboardModuleCacher extends ThinkUpUnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testGetClientVisualizationData() {
        $client_data = array(
            'Client 1' => 50,
            'Client 2' => 10,
        );

        $result = DashboardModuleCacher::getClientUsageVisualizationData($client_data);
        $this->assertEqual(gettype($result), 'string');

        $visualization_object = json_decode($result);
        $this->assertEqual(sizeof($visualization_object->rows), 2);
        $this->assertEqual(sizeof($visualization_object->cols), 2);

        $this->assertEqual($visualization_object->cols[0]->label, 'Client');
        $this->assertEqual($visualization_object->cols[1]->label, 'Posts');

        $this->assertEqual($visualization_object->rows[0]->c[0]->v, 'Client 1');
        $this->assertEqual($visualization_object->rows[0]->c[0]->f, 'Client 1');
        $this->assertEqual($visualization_object->rows[0]->c[1]->v, 50);

        $this->assertEqual($visualization_object->rows[1]->c[0]->v, 'Client 2');
        $this->assertEqual($visualization_object->rows[1]->c[1]->v, 10);
    }

    public function testGetClickStatsVisualizationData() {
        $click_stats = array(
        array('post_text'=>'Black Mirror punched me in the gut this weekend. Highly recommended. http://t.co/AnczD4Jc '.
        'Thx @annaleen  & @fraying',
        'click_count' => 50),
        array('post_text'=>'@saenz a geeky uncle&#39;s only <span class="googid">+Sprint</span> http://t.co/cxZTmWhk',
        'click_count' => 150),
        array('post_text'=>'I\'ll admit Glee made me cry last night. Then it made me cringe. http://t.co/lgjaJWcW ',
        'click_count' => 23),
        );

        $result = DashboardModuleCacher::getClickStatsVisualizationData($click_stats);
        $this->assertEqual(gettype($result), 'string');

        $visualization_object = json_decode($result);
        $this->assertEqual(sizeof($visualization_object->rows), 3);
        $this->assertEqual(sizeof($visualization_object->cols), 2);

        $this->assertEqual($visualization_object->cols[0]->label, 'Link');
        $this->assertEqual($visualization_object->cols[1]->label, 'Clicks');

        $this->assertEqual($visualization_object->rows[0]->c[0]->v,
        'Black Mirror punched me in the gut this weekend. Highly recommended. http://t.co/AnczD4Jc Thx @annal...');
        $this->assertEqual($visualization_object->rows[0]->c[1]->v, 50);
        $this->assertEqual($visualization_object->rows[1]->c[0]->v,
        "@saenz a geeky uncle's only +Sprint http://t.co/cxZTmWhk...");
        $this->assertEqual($visualization_object->rows[1]->c[1]->v, 150);

        $this->assertEqual($visualization_object->rows[2]->c[0]->v,
        'I\'ll admit Glee made me cry last night. Then it made me cringe. http://t.co/lgjaJWcW ...');
        $this->assertEqual($visualization_object->rows[2]->c[1]->v, 23);
    }
}
