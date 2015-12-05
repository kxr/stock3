#!/bin/bash


while read line
do
	iid=$(echo $line | cut -d',' -f1)
	iname=$(echo $line | cut -d',' -f2)
	ixstock=$(echo $line | cut -d ',' -f3)


echo "Adding Item #$iid $iname"
mysql -uroot -pmysqlroot ktdb_stock -e "INSERT INTO items VALUES ( '$iid', '$iname', '', 0);"
echo "Adding Old Stock qty=$ixstock"
mysql -uroot -pmysqlroot ktdb_stock -e "INSERT INTO purchase_transactions VALUES ('', '`date +%Y-%m-%d`', 'Cash', '$iid', '', '', 0, $ixstock, 'Old Stock', '`date +%s`');"
echo "----"
echo; echo; echo;
done < items_with_xstock.csv
