import Head from "@/Components/Head.jsx";
import NavBar from "@/Components/NavBar.jsx";
import React, {useRef} from "react";
import {Button, Col, Container, Input, Row, Table, UncontrolledCollapse} from "reactstrap";
import {exportComponentAsPNG} from "react-component-export-image";
import Filter from "@/Components/Filter.jsx";
import {useForm, usePage} from "@inertiajs/react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    ResponsiveContainer, LabelList, PieChart, Pie, Cell
} from "recharts";

export default () => {
    const {
        brunches,
        filtersAndSort,
        barChartData,
        pieChartData,
        statsBrunchRate,
        brunchesStat
    } = usePage().props;

    const componentRef = useRef(null);

    const {
        data,
        setData,
        get,
        processing
    } = useForm({
        date: 'date' in filtersAndSort && Array.isArray(filtersAndSort.date) ? filtersAndSort.date.map(stringDate => new Date(stringDate)) : [],
        brunches: 'brunches' in filtersAndSort ? brunches.filter(item => filtersAndSort.brunches.map(Number).includes(item.value)) : [],
        platform: 'platform' in filtersAndSort ? filtersAndSort.platform.map(item => ({label: item, value: item})) : [],
        sort: filtersAndSort.sort ?? '',
        orderBy: filtersAndSort.orderBy ?? '',
        commit: false,
    })

    return <>
        <Head title="Статистика"/>
        <NavBar/>
        <Container fluid>
            <Row className="my-3">
                <Col>
                    <h2>Статистика</h2>
                    <Container fluid className="p-0">
                        <Row>
                            <Col xl={8} className="p-0">
                                <Button
                                    className="w-100 m-0 mb-1"
                                    color="primary"
                                    id="toggler"
                                >
                                    Фильтр
                                </Button>
                                <UncontrolledCollapse toggler="#toggler">
                                    <Filter data={data} get={get} setData={setData} processing={processing}/>
                                </UncontrolledCollapse>
                            </Col>
                            <Col xl={1}>
                                <Button onClick={() => exportComponentAsPNG(componentRef, {
                                    fileName: "statistics",
                                    html2CanvasOptions: {backgroundColor: "#fff", scale: 1}
                                })}><i className="ni ni-album-2"></i></Button>
                            </Col>
                        </Row>
                    </Container>
                </Col>
            </Row>
            <div ref={componentRef}>
                <Row className="h-100vh">
                    <Col xl={2} className="mb-4">
                        <span>Общее число отзывов</span>
                        <ResponsiveContainer width="100%" height={300}>
                            <PieChart>
                                <Pie
                                    data={pieChartData}
                                    dataKey="value"
                                    nameKey="name"
                                    cx="50%"
                                    cy="50%"
                                    innerRadius={50}
                                    outerRadius={75}
                                    // paddingAngle={1}
                                    labelLine={false}
                                    label={({value, percent}) => `${value} (${percent}%)`}
                                    isAnimationActive={true}
                                >
                                    {pieChartData.map((entry, i) => (
                                        <Cell key={`cell-${i}`} fill={entry.color}/>
                                    ))}
                                </Pie>
                            </PieChart>
                        </ResponsiveContainer>
                    </Col>
                    <Col xl={10}>
                        <span>Число отзывов по филиалам</span>
                        <ResponsiveContainer width="100%" height={300}>
                            <BarChart
                                data={barChartData}
                            >
                                <CartesianGrid strokeDasharray="3 3"/>
                                <XAxis
                                    dataKey="name"
                                    angle={20}           // угол наклона текста (в градусах)
                                    textAnchor="start"      // выравнивание текста относительно оси
                                    height={45}           // увеличиваем высоту под ось, чтобы текст не обрезался
                                    interval={0}          // показывать все подписи (по умолчанию Recharts может пропускать)
                                    tick={{fontSize: 12}}
                                    scale="band"
                                    type="category"
                                    tickAlignWithBand={true}
                                />
                                <YAxis/>
                                <Bar dataKey="best" fill="#4fd69c" name="Положительный">
                                    <LabelList dataKey="best" position="top"/>
                                </Bar>

                                <Bar dataKey="good" fill="#FFC107" name="Нейтральный">
                                    <LabelList dataKey="good" position="top"/>
                                </Bar>

                                <Bar dataKey="bad" fill="#f75676" name="Отрицательный">
                                    <LabelList dataKey="bad" position="top"/>
                                </Bar>

                            </BarChart>
                        </ResponsiveContainer>
                    </Col>
                    <Col className="pr-0" xl={4}>
                        <span>Соотношение оценок по филиалам</span>
                        <ResponsiveContainer width="100%" height={600}>
                            <BarChart
                                data={barChartData}
                                layout="vertical"
                            >
                                <XAxis type="number" domain={[0, 100]} tickFormatter={(v) => v + "%"}/>
                                <YAxis dataKey="name" type="category" width={120}/>

                                {/* Положительные */}
                                <Bar dataKey="best_p" stackId="a" fill="#4fd69c" name="Положительные">
                                    <LabelList
                                        dataKey="best_title"
                                        position="center"
                                        fill="#fff"
                                        fontSize={14}
                                    />
                                </Bar>

                                {/* Нейтральные */}
                                <Bar dataKey="good_p" stackId="a" fill="#FFC107" name="Нейтральные">
                                    <LabelList
                                        dataKey="good_title"
                                        position="center"
                                        fill="#000"
                                        fontSize={14}
                                    />
                                </Bar>

                                {/* Отрицательные */}
                                <Bar dataKey="bad_p" stackId="a" fill="#f75676" name="Отрицательные">
                                    <LabelList
                                        dataKey="bad_title"
                                        position="center"
                                        fill="#fff"
                                        fontSize={14}
                                    />
                                </Bar>
                            </BarChart>
                        </ResponsiveContainer>
                    </Col>
                    <Col xl={4} className="pl-0">
                        <span>Примечания</span>
                        <div className="d-flex flex-column"
                             style={{maxHeight: 567, paddingTop: 8, paddingBottom: 8, gap: 10}}>
                            {brunchesStat.map(() => <Input className="p-0 m-0" type="textarea" style={{height: 'auto', minHeight: 0}}/>)}
                        </div>
                    </Col>

                    {/*<Col xl={6}>


                    <h2 className='fs-5 font-weight-bold text-center'>По филиалам (Процетовка)</h2>
                    <Table size="sm" hover responsive>
                        <thead>
                        <tr>
                            <th col className="col-2 text-center">Филиал</th>
                            <th className="table-success text-center col-1"><span>5</span>
                            </th>
                            <th className="table-warning text-center col-1"><span>4</span>
                            </th>
                            <th className="table-danger text-center col-1"><span>1-3</span>
                            </th>
                            <th>Комментарий</th>
                        </tr>
                        </thead>
                        <tbody>
                        {statsByBranches.map(item => {
                            return <tr>
                                <td className={"text-right " + ((item['percent5'] >= 80) ? "table-success"  : ((item['percent5'] >= 60 ? "table-warning" : "table-danger")))}>{item.name}</td>
                                <td className="text-center">{item['5']} {(item['percent5'] !== 0) ? `(${item['percent5']}%)` : '-'}</td>
                                <td className="text-center">{item['4']} {(item['percent4'] !== 0) ? `(${item['percent4']}%)` : '-'}</td>
                                <td className="text-center">{item['1-3']} {(item['percent1-3'] !== 0) ? `(${item['percent1-3']}%)` : '-'}</td>
                                <td className="p-0"><Input bsSize="sm" type="textarea" className="border-0 m-0 h-100"/></td>
                            </tr>
                        })}
                        </tbody>
                    </Table>
                </Col>*/}
                    <Col xl={4}>
                        <span>Срение оценки по филиалам</span>
                        <Table size="sm" hover responsive>
                            <thead>
                            <tr>
                                <th className="col-1"></th>
                                <th className="text-center">Средний за период</th>
                                <th className="text-center">2ГИС (текущий)</th>
                                <th className="text-center">Яндекс.Еда (текущий)</th>
                            </tr>
                            </thead>
                            <tbody>
                            {statsBrunchRate.map(item => {
                                return <tr>
                                    <td className="text-right">{item.name}</td>
                                    <td className="text-center">{item.selectedDateRange}</td>
                                    <td className="text-center">{item.twoGis}</td>
                                    <td className="text-center">{item.yEda}</td>
                                </tr>
                            })}
                            </tbody>
                        </Table>
                    </Col>
                </Row>
            </div>
        </Container>
    </>;
}
