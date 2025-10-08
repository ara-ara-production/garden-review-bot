import Head from "@/Components/Head.jsx";
import NavBar from "@/Components/NavBar.jsx";
import React from "react";
import {Button, Col, Container, Row, Table, UncontrolledCollapse} from "reactstrap";
import Filter from "@/Components/Filter.jsx";
import {useForm, usePage} from "@inertiajs/react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer
} from "recharts";


export default () => {
    const {
        brunches,
        filtersAndSort,
        routes,
        statsDataChart,
        statsDataPercent,
        statsBrunchRate} = usePage().props;

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

    const datar = [
        {name: "Янв", sales: 4000, er: 2342},
        {name: "Фев", sales: 3000},
        {name: "Мар", sales: 2000},
        {name: "Апр", sales: 2780},
        {name: "Май", sales: 1890},
    ];

    console.log(statsDataPercent)

    return <>
        <Head title="Статистика"/>
        <NavBar/>
        <Container fluid>
            <Row className="my-3">
                <Col>
                    <h2>Статистика</h2>
                    <Container fluid className="p-0">
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
                    </Container>
                </Col>
            </Row>
            <Row>
                <Col xl={6}>
                    <Table size="sm">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Оценка</th>
                            <th>Кол-во</th>
                            <th>Доля в %</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr className="table-success">
                            <td>Положительный</td>
                            <td>5</td>
                            <td>{statsDataPercent[5]['count']}</td>
                            <td>{statsDataPercent[5]['percent']} %</td>
                        </tr>
                        <tr className="table-warning">
                            <td>Нейтральный</td>
                            <td>4</td>
                            <td>{statsDataPercent[4]['count']}</td>
                            <td>{statsDataPercent[4]['percent']} %</td>
                        </tr>
                        <tr className="table-danger">
                            <td>Отрицательный</td>
                            <td>1 - 3</td>
                            <td>{statsDataPercent['1-3']['count']}</td>
                            <td>{statsDataPercent['1-3']['percent']} %</td>
                        </tr>
                        </tbody>
                    </Table>
                </Col>
                <Col xl={6}>
                    <Table size="sm">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Выбранный период</th>
                            <th>2ГИС (текущий)</th>
                        </tr>
                        </thead>
                        <tbody>
                        {statsBrunchRate.map(item => {
                            return <tr>
                                <td>{item.name}</td>
                                <td>{item.avg}</td>
                                <td>{item.twoGis}</td>
                            </tr>
                        })}
                        </tbody>
                    </Table>
                </Col>
                <Col>
                    <ResponsiveContainer width="100%" height={300}>
                        <BarChart
                            data={statsDataChart}
                            margin={{top: 20, right: 30, left: 20, bottom: 5}}
                        >
                            <CartesianGrid strokeDasharray="3 3"/>
                            <XAxis dataKey="name"/>
                            <YAxis/>
                            <Tooltip/>
                            <Legend/>
                            <Bar dataKey="5" fill="#4fd69c" name="5"/>
                            <Bar dataKey="4" fill="#fc7c5f" name="4"/>
                            <Bar dataKey="1-3" fill="#f75676" name="1-3"/>
                        </BarChart>
                    </ResponsiveContainer>
                </Col>
            </Row>
        </Container>
    </>;
}
