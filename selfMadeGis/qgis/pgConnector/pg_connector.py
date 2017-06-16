# -*- coding: utf-8 -*-
# -*- coding: cp1251 -*-
"""
/***************************************************************************
 pgConnector
                                 A QGIS plugin
 connect to pg database
                              -------------------
        begin                : 2017-06-09
        git sha              : $Format:%H$
        copyright            : (C) 2017 by Yurii Shpylovyi
        email                : yurii.shpylovyi@gmail.com
 ***************************************************************************/

/***************************************************************************
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
"""
from PyQt4.QtCore import QSettings, QTranslator, qVersion, QCoreApplication, QFileInfo
from PyQt4.QtGui import QAction, QIcon, QTableWidgetItem, QTableView, QColumnView
# Initialize Qt resources from file resources.py
import resources
# Import the code for the dialog
from pg_connector_dialog import pgConnectorDialog
import os.path
import psycopg2
from qgis.core import QgsProject, QgsMapLayerRegistry

class pgConnector:
    """QGIS Plugin Implementation."""

    def __init__(self, iface):
        """Constructor.

        :param iface: An interface instance that will be passed to this class
            which provides the hook by which you can manipulate the QGIS
            application at run time.
        :type iface: QgsInterface
        """
        # Save reference to the QGIS interface
        self.iface = iface
        # initialize plugin directory
        self.plugin_dir = os.path.dirname(__file__)
        # initialize locale
        locale = QSettings().value('locale/userLocale')[0:2]
        locale_path = os.path.join(
            self.plugin_dir,
            'i18n',
            'pgConnector_{}.qm'.format(locale))

        if os.path.exists(locale_path):
            self.translator = QTranslator()
            self.translator.load(locale_path)

            if qVersion() > '4.3.3':
                QCoreApplication.installTranslator(self.translator)

        self.dlg = pgConnectorDialog()       
        # Declare instance attributes
        self.actions = []
        self.menu = self.tr(u'&pg connector')
        # TODO: We are going to let the user set this up in a future iteration
        self.toolbar = self.iface.addToolBar(u'pgConnector')
        self.toolbar.setObjectName(u'pgConnector')

        self.dlg.label.clear()
        
        #self.dlg.cc_pit_sign.clicked.connect(self.city_name)
        self.dlg.cableChannelPitsDataUpdate.clicked.connect(lambda: self.postgres_query('cableChannelPitsDataUpdate'))
        self.dlg.cableChannelChannelDataUpdate.clicked.connect(lambda: self.postgres_query('cableChannelChannelDataUpdate'))
        self.dlg.ctvTopologyLoad.clicked.connect(lambda: self.postgres_query('ctvTopologyLoad'))
        self.dlg.ctvTopologyUpdate.clicked.connect(lambda: self.postgres_query('ctvTopologyUpdate'))
    # noinspection PyMethodMayBeStatic
    def tr(self, message):
        """Get the translation for a string using Qt translation API.

        We implement this ourselves since we do not inherit QObject.

        :param message: String for translation.
        :type message: str, QString

        :returns: Translated version of message.
        :rtype: QString
        """
        # noinspection PyTypeChecker,PyArgumentList,PyCallByClass
        return QCoreApplication.translate('pgConnector', message)


    def add_action(
        self,
        icon_path,
        text,
        callback,
        enabled_flag=True,
        add_to_menu=True,
        add_to_toolbar=True,
        status_tip=None,
        whats_this=None,
        parent=None):
        """Add a toolbar icon to the toolbar.

        :param icon_path: Path to the icon for this action. Can be a resource
            path (e.g. ':/plugins/foo/bar.png') or a normal file system path.
        :type icon_path: str

        :param text: Text that should be shown in menu items for this action.
        :type text: str

        :param callback: Function to be called when the action is triggered.
        :type callback: function

        :param enabled_flag: A flag indicating if the action should be enabled
            by default. Defaults to True.
        :type enabled_flag: bool

        :param add_to_menu: Flag indicating whether the action should also
            be added to the menu. Defaults to True.
        :type add_to_menu: bool

        :param add_to_toolbar: Flag indicating whether the action should also
            be added to the toolbar. Defaults to True.
        :type add_to_toolbar: bool

        :param status_tip: Optional text to show in a popup when mouse pointer
            hovers over the action.
        :type status_tip: str

        :param parent: Parent widget for the new action. Defaults None.
        :type parent: QWidget

        :param whats_this: Optional text to show in the status bar when the
            mouse pointer hovers over the action.

        :returns: The action that was created. Note that the action is also
            added to self.actions list.
        :rtype: QAction
        """

        # Create the dialog (after translation) and keep reference
        

        icon = QIcon(icon_path)
        action = QAction(icon, text, parent)
        action.triggered.connect(callback)
        action.setEnabled(enabled_flag)

        if status_tip is not None:
            action.setStatusTip(status_tip)

        if whats_this is not None:
            action.setWhatsThis(whats_this)

        if add_to_toolbar:
            self.toolbar.addAction(action)

        if add_to_menu:
            self.iface.addPluginToVectorMenu(
                self.menu,
                action)

        self.actions.append(action)

        return action

    def initGui(self):
        """Create the menu entries and toolbar icons inside the QGIS GUI."""

        icon_path = ':/plugins/pgConnector/icon.png'
        self.add_action(
            icon_path,
            text=self.tr(u'pg_connector'),
            callback=self.run,
            parent=self.iface.mainWindow())


    def unload(self):
        """Removes the plugin menu item and icon from QGIS GUI."""
        for action in self.actions:
            self.iface.removePluginVectorMenu(
                self.tr(u'&pg connector'),
                action)
            self.iface.removeToolBarIcon(action)
        # remove the toolbar
        del self.toolbar

    def postgres_query(self, button):
        self.dlg.label.clear()
        city = None
        for item in QgsMapLayerRegistry.instance().mapLayers():
            if 'buildings' in item:
                city = item[0:item.find('_')]
        #-----------------------------------
        queryDict = {
            'cableChannelChannelDataUpdate':{
                'tableExtantion':'_cable_channels_channels',
                'fileType':'csv',
                'fileLink':'archive'
            },
            'cableChannelPitsDataUpdate':{
                'tableExtantion': None,
                'fileType':None,
                'fileLink':None
            },
            'ctvTopologyLoad':{
                'tableExtantion':'_ctv_topology',
                'fileType':'csv',
                'fileLink':'cubic'
            },
            'ctvTopologyUpdate':{
                'tableExtantion':'_ctv_topology',
                'fileType':'csv',
                'fileLink':'cubic'
            },
            'ethernetTopologyLoad':{
                'tableExtantion':'_switches',
                'fileType':'csv',
                'fileLink':'cubic'
            },
            'etherTopologyUpdate':{
                'tableExtantion':'_switches',
                'fileType':'csv',
                'fileLink':'cubic'
            }

        }
        #----adding link to file if needed---------
        for k, v in queryDict.iteritems():
            if queryDict[k]['fileLink'] == 'archive' :
                queryDict[k]['linkStorage'] = '/var/www/QGIS-Web-Client-master/site/'+queryDict[k]['fileType']+'/'+queryDict[k]['fileLink']+'/'+city+'/'+city + queryDict[k]['tableExtantion'] + '.' + queryDict[k]['fileType']
            elif queryDict[k]['fileLink'] == 'cubic' :
                queryDict[k]['linkStorage'] = '/var/www/QGIS-Web-Client-master/site/'+queryDict[k]['fileType']+'/'+queryDict[k]['fileLink']+'/'+queryDict[k]['tableExtantion']+'/'+city + queryDict[k]['tableExtantion'] + '.' + queryDict[k]['fileType']
        #-----adding arrays of postgresql queries------
        #---cable channels channels part---------------
        queryDict['cableChannelChannelDataUpdate']['queryList'] =  [
            "CREATE TEMP TABLE temp(id serial, pit_id_1 integer, pit_id_2 integer, distance varchar(100));select copy_for_testuser('temp( pit_id_1, pit_id_2, distance)', '"+queryDict['cableChannelChannelDataUpdate']['linkStorage'] +"', ';', 'windows-1251');INSERT INTO "+city+"."+city+"_cable_channels_channels( pit_id_1, pit_id_2, distance) SELECT pit_id_1, pit_id_2, distance FROM temp t WHERE not exists (SELECT 1 FROM "+city+"."+city+"_cable_channels_channels c where t.pit_id_1 = c.pit_id_1 and t.pit_id_2 = c.pit_id_2); ",
            "UPDATE "+city+"."+city+"_cable_channels_channels SET pit_1 = "+city+"_cable_channel_pits.pit_number, she_n_1 = "+city+"_cable_channel_pits.pit_district, microdistrict_1 = "+city+"_cable_channel_pits.microdistrict, pit_1_geom = "+city+"_cable_channel_pits.geom FROM "+city+"."+city+"_cable_channel_pits WHERE pit_id_1 = "+city+"_cable_channel_pits.pit_id ; UPDATE "+city+"."+city+"_cable_channels_channels SET pit_2 = "+city+"_cable_channel_pits.pit_number, she_n_2 = "+city+"_cable_channel_pits.pit_district, microdistrict_2 = "+city+"_cable_channel_pits.microdistrict, pit_2_geom = "+city+"_cable_channel_pits.geom FROM "+city+"."+city+"_cable_channel_pits WHERE pit_id_2 = "+city+"_cable_channel_pits.pit_id; UPDATE "+city+"."+city+"_cable_channels_channels SET channel_geom = ST_MakeLine(pit_1_geom, pit_2_geom) WHERE pit_1_geom IS NOT NULL AND pit_2_geom IS NOT NULL;",
            "UPDATE "+city+"."+city+"_cable_channels_channels SET pit_1_geom = ST_StartPoint(channel_geom), pit_2_geom = ST_EndPoint(channel_geom) WHERE pit_1_geom IS NULL AND pit_2_geom IS NULL; UPDATE "+city+"."+city+"_cable_channels_channels SET pit_1 = "+city+"_cable_channel_pits.pit_number, pit_id_1 = "+city+"_cable_channel_pits.pit_id, she_n_1 = "+city+"_cable_channel_pits.pit_district, microdistrict_1 = "+city+"_cable_channel_pits.microdistrict, pit_1_geom = "+city+"_cable_channel_pits.geom FROM "+city+"."+city+"_cable_channel_pits WHERE ST_Equals(pit_1_geom, "+city+"_cable_channel_pits.geom)  AND "+city+"_cable_channels_channels.pit_1_geom IS NOT NULL AND "+city+"_cable_channel_pits.geom IS NOT NULL AND "+city+"_cable_channels_channels.pit_id_1 IS NULL; UPDATE "+city+"."+city+"_cable_channels_channels SET pit_2 = "+city+"_cable_channel_pits.pit_number , pit_id_2 = "+city+"_cable_channel_pits.pit_id, she_n_2 = "+city+"_cable_channel_pits.pit_district, microdistrict_2 = "+city+"_cable_channel_pits.microdistrict, pit_2_geom = "+city+"_cable_channel_pits.geom FROM "+city+"."+city+"_cable_channel_pits WHERE ST_Equals(pit_2_geom, "+city+"_cable_channel_pits.geom) AND "+city+"_cable_channels_channels.pit_2_geom IS NOT NULL AND "+city+"_cable_channel_pits.geom IS NOT NULL  AND "+city+"_cable_channels_channels.pit_id_2 IS NULL;",
            "UPDATE "+city+"."+city+"_cable_channels_channels SET she_1 ='"+'ПГС№'.decode('cp1251')+"'||"+city+"_coverage.coverage_zone FROM "+city+"."+city+"_coverage WHERE ST_Contains("+city+"."+city+"_coverage.geom_area, "+city+"."+city+"_cable_channels_channels.pit_1_geom) and "+city+"."+city+"_coverage.geom_area is not null; UPDATE "+city+"."+city+"_cable_channels_channels SET she_2 ='"+'ПГС№'.decode('cp1251')+"'||"+city+"_coverage.coverage_zone FROM "+city+"."+city+"_coverage WHERE ST_Contains("+city+"."+city+"_coverage.geom_area, "+city+"."+city+"_cable_channels_channels.pit_2_geom) and "+city+"."+city+"_coverage.geom_area is not null;"
        ]
        queryDict['cableChannelPitsDataUpdate']['queryList'] = [
            "UPDATE "+city+"."+city+"_cable_channel_pits SET microdistrict ="+city+"_microdistricts.micro_district FROM "+city+"."+city+"_microdistricts WHERE ST_Contains("+city+"."+city+"_microdistricts.coverage_geom, "+city+"."+city+"_cable_channel_pits.geom) ;UPDATE "+city+"."+city+"_cable_channel_pits SET district ="+city+"_microdistricts.district FROM "+city+"."+city+"_microdistricts WHERE ST_Contains("+city+"."+city+"_microdistricts.coverage_geom, "+city+"."+city+"_cable_channel_pits.geom) ;UPDATE "+city+"."+city+"_cable_channel_pits SET pit_district ="+city+"_coverage.notes FROM "+city+"."+city+"_coverage WHERE ST_Contains("+city+"."+city+"_coverage.geom_area, "+city+"."+city+"_cable_channel_pits.geom) and "+city+"."+city+"_coverage.geom_area is not null;"
        ]
        #-----ctv topology part-----------------------
        queryDict['ctvTopologyLoad']['queryList'] = [
            "CREATE TEMP TABLE temp( id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100)); select copy_for_testuser('temp(CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE )', '"+queryDict['ctvTopologyLoad']['linkStorage'] +"', ',', 'utf-8') ; UPDATE "+city+"."+city+"_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = temp.COMENT, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE "+city+"."+city+"_ctv_topology.cubic_code = temp.CODE; INSERT INTO "+city+"."+city+"_ctv_topology(cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name, cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id) SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM "+city+"."+city+"_ctv_topology WHERE cubic_code IS NOT NULL);"
        ]
        queryDict['ctvTopologyUpdate']['queryList'] = [
            "CREATE TEMP TABLE temp( id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100)); select copy_for_testuser('temp( CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE )', '"+queryDict['ctvTopologyUpdate']['linkStorage'] +"', ',', 'utf-8') ; DELETE FROM "+city+"."+city+"_ctv_topology WHERE cubic_code NOT IN(SELECT DISTINCT CODE FROM temp) ;UPDATE "+city+"."+city+"_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = temp.COMENT, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE " +city+"."+city+"_ctv_topology.cubic_code = temp.CODE; SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM "+city+"."+city+"_ctv_topology WHERE cubic_code IS NOT NULL);",
            "UPDATE "+city+"."+city+"_ctv_topology SET equipment_geom = CASE WHEN cubic_name LIKE '"+'%Магистральный распределительный узел%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_firstpoint WHEN cubic_name LIKE '"+'%Магістральний оптичний вузол%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_thirdpoint WHEN cubic_name LIKE '"+'%Оптический узел%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_fourthpoint WHEN cubic_name LIKE '"+'%Оптичний приймач%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_firstpoint WHEN cubic_name LIKE '"+'%Передатчик оптический%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_secondpoint WHEN cubic_name LIKE '"+'%Порт ОК%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_secondpoint WHEN cubic_name LIKE '"+'%Домовой узел%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_thirdpoint WHEN cubic_name LIKE '"+'%Ответвитель магистральный%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_fourthpoint WHEN cubic_name LIKE '"+'%Распределительный стояк%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_secondpoint WHEN cubic_name LIKE '"+'%Магистральный узел%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_secondpoint WHEN cubic_name LIKE '"+'%Субмагистральный узел%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_thirdpoint WHEN cubic_name LIKE '"+'%Кросс-муфта%'.decode('utf-8')+"' THEN "+city+"."+city+"_buildings.building_geom_fourthpoint END FROM  "+city+"."+city+"_buildings WHERE "+city+"."+city+"_ctv_topology.equipment_geom IS NULL AND "+city+"."+city+"_ctv_topology.cubic_house_id = "+city+"."+city+"_buildings.cubic_house_id;",
            "CREATE TEMP TABLE tmp AS SELECT cubic_code, equipment_geom, cubic_name, cubic_street, cubic_house FROM "+city+"."+city+"_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM "+city+"."+city+"_ctv_topology WHERE cubic_ou_code IS NOT NULL); UPDATE "+city+"."+city+"_ctv_topology SET mother_equipment_geom = tmp.equipment_geom,  cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE "+city+"_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;  UPDATE "+city+"."+city+"_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE "+city+"_ctv_topology.mother_equipment_geom IS NOT null AND "+city+"_ctv_topology.equipment_geom IS NOT NULL;",
            "CREATE TEMP TABLE tmp AS SELECT cubic_name, cubic_street, cubic_house, cubic_code FROM "+city+"."+city+"_ctv_topology WHERE cubic_code IN (SELECT DISTINCT cubic_ou_code FROM "+city+"."+city+"_ctv_topology WHERE cubic_ou_code IS NOT NULL) ;UPDATE  "+city+"."+city+"_ctv_topology SET cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_name, cubic_ou_house = tmp.cubic_name FROM tmp WHERE "+city+"."+city+"_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;",
            "UPDATE "+city+"."+city+"_ctv_topology SET archive_link = CASE  WHEN cubic_name like '"+'%Магистральный распределительный узел%'.decode('utf-8')+"' THEN 'http://10.112.129.170/qgis-ck/tmp/archive/"+city+"/topology/mdod/'||cubic_code||'/' WHEN cubic_name like '"+'%Оптический узел%'.decode('utf-8')+"' THEN 'http://10.112.129.170/qgis-ck/tmp/archive/"+city+"/topology/nod/'||cubic_code||'/' WHEN cubic_name like '"+'%Оптичний приймач%'.decode('utf-8')+"' THEN 'http://10.112.129.170/qgis-ck/tmp/archive/"+city+"/topology/op/'||cubic_code||'/' WHEN cubic_name like '"+'%Передатчик оптический%'.decode('utf-8')+"' THEN 'http://10.112.129.170/qgis-ck/tmp/archive/"+city+"/topology/ot/'||cubic_code||'/' WHEN cubic_name like '"'%Кросс-муфта%'.decode('utf-8')+"' THEN 'http://10.112.129.170/qgis-ck/tmp/archive/"+city+"/topology/cc/'||cubic_code||'/' END ;",
            "UPDATE "+city+"."+city+"_ctv_topology SET microdistrict ="+city+"_microdistricts.micro_district FROM "+city+"."+city+"_microdistricts WHERE ST_Contains("+city+"_microdistricts.coverage_geom, "+city+"_ctv_topology.equipment_geom) ;UPDATE "+city+"."+city+"_ctv_topology SET district ="+city+"_microdistricts.district FROM "+city+"."+city+"_microdistricts WHERE ST_Contains("+city+"_microdistricts.coverage_geom, "+city+"_ctv_topology.equipment_geom) ;UPDATE "+city+"."+city+"_ctv_topology SET she_num ="+city+"_coverage.coverage_zone FROM "+city+"."+city+"_coverage WHERE ST_Contains("+city+"_coverage.geom_area, "+city+"_ctv_topology.equipment_geom) and "+city+"."+city+"_coverage.geom_area is not null ;"
        ]
        #------ethernet topology part-----------------
        queryDict['ethernetTopologyLoad']['queryList'] = [
            ""
        ]
        queryDict['etherTopologyUpdate']['queryList'] = [
            ""
        ]
        #-----------------------------------

        conn = psycopg2.connect("dbname='postgres' host=10.112.129.170 port=5432 user='simpleuser' password='simplepassword'")
        cur = conn.cursor()
        #--- postgres query sender part--------------
        for query in queryDict[button]['queryList']:
            cur.execute(query)
            conn.commit()
            if cur.description!= None:
                result = cur.fetchall()
                self.dlg.listWidget.clear()
                for row in result:
                    self.dlg.listWidget.addItem('--'.join(items if items !=None else '////' for items in row))
        #-----------------------------------------------
        self.dlg.lineEdit.setText(''.join(queryDict[button]['queryList']))
        

        self.dlg.label.setText(button)

        # if cur.description!= None:
        #     result = cur.fetchall()
        #     self.dlg.listWidget.clear()
        #     for row in result:
        #         self.dlg.listWidget.addItem('--'.join(''.join(items) for items in row))
        # else:
        #     cur.close()
        #     self.dlg.listWidget.addItem('None')

    def run(self):
        """Run method that performs all the real work"""
        # show the dialog
        self.dlg.show()
        # Run the dialog event loop
        result = self.dlg.exec_()
        # See if OK was pressed
        if result:
            # Do something useful here - delete the line containing pass and
            # substitute with your code.
            pass
