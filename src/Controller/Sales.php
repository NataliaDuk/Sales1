<?php

namespace App\Controller;

use App\Model\SalesModel;

//Работа со стилями ячеек
use PhpOffice\PhpSpreadsheet\Style\{Fill, Font, Border, Alignment};
//Запись файла
use PhpOffice\PhpSpreadsheet\IOFactory;
// Работа со структурой документа
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Sales extends Table
{
    protected string $tableName = "sale";

    public function __construct()
    {
        parent::__construct();
        $config = include __DIR__ . "/../../config.php";
        $config["table"] = $this->tableName;
        $this->pageSize = $config["page_size"];
        $this->model = new SalesModel($config);

    }

    /** показывает страницу для редактирования строки
     * @throws \Exception
     */
    public function actionShowEdit(): void
    {
        parent::actionShowEdit(); // TODO: Change the autogenerated stub
        $this
            ->view
            ->addData([
                "groupList1" => $this->model->getGroupListUsers(),
                "groupList2" => $this->model->getGroupListCountries(),
                "groupList3" => $this->model->getGroupListProdukt(),
                "groupList4" => $this->model->getGroupListCustomers()
            ])
            ->setTemplate("Sales/add_edit");
    }

    /**
     * показывает страницу для добавления новой строки
     */
    public function actionShowAdd(): void
    {
        parent::actionShowAdd(); // TODO: Change the autogenerated stub
        $this
            ->view
            ->addData([
                "groupList1" => $this->model->getGroupListUsers(),
                "groupList2" => $this->model->getGroupListCountries(),
                "groupList3" => $this->model->getGroupListProdukt(),
                "groupList4" => $this->model->getGroupListCustomers()
            ])
            ->setTemplate("Sales/add_edit");
    }

    /**
     * показывает таблицу
     */
    public function actionShow(): void
    {
        parent::actionShow(); // TODO: Change the autogenerated stub
        $this->view->setTemplate("Sales/show");
    }

    public function actionAdd(): void
    {
        parent::actionAdd(); // TODO: Change the autogenerated stub
        $this->view->setTemplate("Sales/add_edit");
    }

    /**
     * осуществляет экспорт данных в Excel
     */
    public function actionExport(): void
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=exportfile.xls");
        $getlist = $this->model->getList();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Структура документа
        $sheet->setCellValue('A1', 'Продажи.');
        $sheet->setCellValue('A2', 'data');
        $sheet->setCellValue('B2', 'предприятие');
        $sheet->setCellValue('C2', 'Покупатель');
        $sheet->setCellValue('D2', 'Страна');
        $sheet->setCellValue('E2', 'Продукция');
        $sheet->setCellValue('F2', 'Вес, тонн');
        $sheet->setCellValue('G2', 'Стоимость, тыс.долл.');
        $count = 3;

        foreach ($getlist as $row) {
            $sheet->setCellValue('A' . $count, $row['data']);
            $sheet->setCellValue('B' . $count, $row['users_id']);
            $sheet->setCellValue('C' . $count, $row['customers_id']);
            $sheet->setCellValue('D' . $count, $row['countries_id']);
            $sheet->setCellValue('E' . $count, $row['produkt_id1']);
            $sheet->setCellValue('F' . $count, $row['weight']);
            $sheet->setCellValue('G' . $count, $row['cost']);
            $count++;
        }
// Получаем ячейки и устаналиваем для них стили
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'underline' => Font::UNDERLINE_NONE,
                'strikethrough' => false,
                'color' => [
                    'rgb' => '000000'
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]
        ]);
        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'underline' => Font::UNDERLINE_NONE,
                'strikethrough' => false,
                'color' => [
                    'rgb' => '000000'
                ]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]
        ]);
        //Установка высоты для 1 строки
        $sheet->getRowDimension(1)->setRowHeight(30);
        //Установка ширины столбцов
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(20);
        //Объединение ячеек
        $sheet->mergeCells('A1:G1');
        //Изменение цвета ячеек
        $spreadsheet->getActiveSheet()->getStyle('A2:G2')
            ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF');

        // Запись на сервер файла excel
        $oWriter = IOFactory::createWriter($spreadsheet, 'Xls');
        $oWriter->save('php://output');
    }

}

