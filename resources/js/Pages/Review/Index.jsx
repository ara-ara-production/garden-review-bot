import NavBar from "@/Components/NavBar.jsx";
import Head from "@/Components/Head.jsx";
import {
    Alert,
    Button,
    Col,
    Container,
    Form,
    FormGroup,
    Label,
    Pagination,
    PaginationItem,
    PaginationLink,
    Row,
    Table
} from "reactstrap";
import {router, useForm, usePage} from "@inertiajs/react";
import React, {useEffect} from "react";

import strBlackDown from '../../../svg/bstr-black-down.svg';
import strBlackUp from '../../../svg/bstr-black-up.svg';
import strGrayDown from '../../../svg/bstr-gray-down.svg';
import strGrayUp from '../../../svg/bstr-gray-up.svg';
import exelIcon from '../../../svg/microsoft-excel-svgrepo-com.svg';
import statisticIcon from '../../../svg/pie-chart-svgrepo-com.svg'
import Select from 'react-select';
import {DateRangePicker} from 'rsuite';
import qs from "qs";

const options = {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
};

export default ({paginator, brunches, filtersAndSort}) => {
    const {routes} = usePage().props;
    const route = '/' + routes.review_table_prefix + '/' + routes.review

    const params = new URLSearchParams(window.location.search);
    params.delete('page');

    const {
        data,
        setData,
        get,
        processing,
        errors,

    } = useForm({
        date: 'date' in filtersAndSort && Array.isArray(filtersAndSort.date) ? filtersAndSort.date.map(stringDate => new Date(stringDate)) : [],
        brunches: 'brunches' in filtersAndSort ? brunches.filter(item => filtersAndSort.brunches.map(Number).includes(item.value)) : [],
        platform: 'platform' in filtersAndSort ? filtersAndSort.platform.map(item => ({label: item, value: item})) : [],
        sort: filtersAndSort.sort ?? '',
        orderBy: filtersAndSort.orderBy ?? '',
        commit: false,
    })

    console.log(data)

    useEffect(() => {
        if (data.commit) {
            get(`/${routes.review_table_prefix}/${routes.review}`);
        }
    }, [data]);


    const submit = (e) => {
        e.preventDefault();
        setData('commit', true)
    }

    return (<>
            <Head title="Отзывы"/>
            <NavBar/>
            <Container fluid>
                <Row className="my-3">
                    <Col>
                        <h2>Отзывы</h2>
                        <Alert color="info">Всего записей: {paginator.total}</Alert>
                        <Container fluid className="p-0">
                            <Col xl={8}>
                                <Form className="row" onSubmit={submit}>
                                    <Col className="border border-primary rounded col-12 mb-4 pt-4">
                                        <FormGroup row className="mb-4">
                                            <Label sm={3}>Дата публикации</Label>
                                            <Col sm={4}>
                                                <DateRangePicker
                                                    // date={}
                                                    value={data.date}
                                                    onChange={e => setData('date', e)}
                                                />
                                            </Col>
                                        </FormGroup>
                                        <FormGroup row className="mb-4">
                                            <Label sm={3}>Филиал</Label>
                                            <Col sm={4}>
                                                <Select
                                                    value={data.brunches}
                                                    onChange={e => {
                                                        setData('brunches', e)
                                                    }}
                                                    options={brunches}
                                                    isMulti
                                                    closeMenuOnSelect={false}
                                                ></Select>
                                            </Col>
                                        </FormGroup>
                                        <FormGroup row className="mb-4">
                                            <Label sm={3}>Платформа</Label>
                                            <Col sm={4}>
                                                <Select
                                                    value={data.platform}
                                                    onChange={e => {
                                                        setData('platform', e)
                                                    }}
                                                    options={
                                                        [
                                                            {
                                                                label: '2Гис',
                                                                value: '2Гис'
                                                            },
                                                            {
                                                                label: 'Бот',
                                                                value: 'Бот'
                                                            }
                                                        ]
                                                    }
                                                    isMulti
                                                    closeMenuOnSelect={false}
                                                ></Select>
                                            </Col>
                                        </FormGroup>
                                        <FormGroup row className="mb-4">
                                            <Col
                                                sm={8}
                                            >
                                                <Button
                                                    className="w-100"
                                                    type="submit"
                                                    value="Сохранить"
                                                    color="primary"
                                                    outline
                                                    disabled={processing}
                                                >Фильтровать</Button>
                                            </Col>
                                            <Col
                                                sm={2}
                                            >
                                                <Button
                                                    className="w-100 d-flex justify-content-center align-items-center"
                                                    color="success"
                                                    outline
                                                    disabled={processing}
                                                    onClick={(() => {
                                                        window.location.href = `/${routes.review_table_prefix}/${routes.review}/export?` + qs.stringify(data)
                                                    })}
                                                >
                                                    <img
                                                        alt="Экспортировать"
                                                        height={20}
                                                        width={20}
                                                        src={exelIcon}
                                                    />
                                                </Button>
                                            </Col>
                                            <Col sm={2}>
                                                <Button
                                                    className="w-100 d-flex justify-content-center align-items-center"
                                                    color="info"
                                                    outline
                                                    disabled={processing}
                                                >
                                                    <img
                                                        alt="Статистика"
                                                        height={20}
                                                        width={20}
                                                        src={statisticIcon}
                                                    />
                                                </Button>
                                            </Col>
                                        </FormGroup>
                                    </Col>
                                </Form>
                            </Col>
                        </Container>
                    </Col>
                </Row>

                <Row className="small">
                    <Col>
                        <Table responsive size="sm" hover>
                            <thead>
                            <tr className="text-center">
                                <th>#</th>
                                <th className="col-1">
                                    <div className="mr-1">Дата публикации отзыва</div>
                                    <div className="d-flex justify-content-center align-items-center gap-2">
                                        <img
                                            src={(data.sort === 'posted_at' && data.orderBy === 'ASC') ? strBlackUp : strGrayUp}
                                            width={15} height={15}
                                            onClick={(e) => {
                                                e.preventDefault();
                                                setData('sort', 'posted_at');
                                                setData('orderBy', 'ASC');
                                                setData('commit', true);
                                            }}/>
                                        <img
                                            src={(data.sort === 'posted_at' && data.orderBy === 'DESC') ? strBlackDown : strGrayDown}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'posted_at');
                                            setData('orderBy', 'DESC');
                                            setData('commit', true);
                                        }}/>
                                    </div>
                                </th>
                                <th className="col-1">
                                    <div>Дата начала проверки</div>
                                    <div className="d-flex justify-content-center align-items-center gap-2">
                                        <img
                                            src={(data.sort === 'start_work_on' && data.orderBy === 'ASC') ? strBlackUp : strGrayUp}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'start_work_on');
                                            setData('orderBy', 'ASC');
                                            setData('commit', true);
                                        }}/>
                                        <img
                                            src={(data.sort === 'start_work_on' && data.orderBy === 'DESC') ? strBlackDown : strGrayDown}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'start_work_on');
                                            setData('orderBy', 'DESC');
                                            setData('commit', true);
                                            router.visit(route + '?column=start_work_on&orderBy=DESC')
                                        }}/>
                                    </div>
                                </th>
                                <th className="col-1">
                                    <div>Дата завершения проверки</div>
                                    <div className="d-flex justify-content-center align-items-center gap-2">
                                        <img
                                            src={(data.sort === 'end_work_on' && data.orderBy === 'ASC') ? strBlackUp : strGrayUp}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'end_work_on');
                                            setData('orderBy', 'ASC');
                                            setData('commit', true);
                                        }}/>
                                        <img
                                            src={data.sort === 'end_work_on' && data.orderBy === 'DESC' ? strBlackDown : strGrayDown}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'end_work_on');
                                            setData('orderBy', 'DESC');
                                            setData('commit', true);
                                        }}/>
                                    </div>
                                </th>
                                <th>Платформа</th>
                                <th>Филиал</th>
                                <th>
                                    <div>Текущий рейтинг</div>
                                    <div className="d-flex justify-content-center align-items-center">
                                        <img
                                            src={data.sort === 'total_brunch_rate' && data.orderBy === 'ASC' ? strBlackUp : strGrayUp}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'total_brunch_rate');
                                            setData('orderBy', 'ASC');
                                            setData('commit', true);
                                        }}/>
                                        <img
                                            src={data.sort === 'total_brunch_rate' && data.orderBy === 'DESC' ? strBlackDown : strGrayDown}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'total_brunch_rate');
                                            setData('orderBy', 'DESC');
                                            setData('commit', true);
                                        }}/>
                                    </div>
                                </th>
                                <th>
                                    <div>Оценка</div>
                                    <div className="d-flex justify-content-center align-items-center gap-2">

                                        <img
                                            src={data.sort === 'score' && data.orderBy === 'ASC' ? strBlackUp : strGrayUp}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'score');
                                            setData('orderBy', 'ASC');
                                            setData('commit', true);
                                        }}/>
                                        <img
                                            src={data.sort === 'score' && data.orderBy === 'DESC' ? strBlackDown : strGrayDown}
                                            width={15} height={15} onClick={(e) => {
                                            e.preventDefault();
                                            setData('sort', 'score');
                                            setData('orderBy', 'DESC');
                                            setData('commit', true);
                                        }}/>
                                    </div>
                                </th>
                                <th className="col-3">Отзыв</th>
                                <th className="col-3">Комментарий управляющего</th>
                                <th className="col-3">Ответ SMM на платформе</th>
                            </tr>
                            </thead>
                            <tbody>
                            {paginator.data.map((row, i) => (<tr>
                                <td key={`review_id-${i}`}>{row.review_id}</td>
                                <td key={`posted_at-${i}`}>{new Date(row.posted_at.replace(/Z$/, '')).toLocaleString('ru-RU', options)}</td>
                                <td key={`start_work_on-${i}`}>{row.start_work_on ? new Date(row.start_work_on).toLocaleString('ru-RU', options) : '-'}</td>
                                <td key={`end_work_on-${i}`}>{row.end_work_on ? new Date(row.end_work_on).toLocaleString('ru-RU', options) : '-'}</td>
                                <td key={`resource-${i}`}>{row.resource.toUpperCase()}</td>
                                <td key={`brunch_name-${i}`}>{row.brunch_name}</td>
                                <td className="text-center" key={`total_brunch_rate-${i}`}>{row.total_brunch_rate}</td>
                                <td className="text-center" key={`score-${i}`}><Alert
                                    color={row.score <= 3 ? "danger" : (row.score == 4 ? "warning" : "success")}>{row.score}</Alert>
                                </td>
                                <td key={`comment-${i}`}>{row.comment}</td>
                                <td key={`control_review-${i}`}>{row.control_review}</td>
                                <td key={`final_answer-${i}`}>{row.final_answer}</td>
                            </tr>))}
                            </tbody>
                        </Table>
                    </Col>
                </Row>
                <Row>
                    <Col className=" d-flex justify-content-center">
                        <Pagination className=" d-flex justify-content-center gap-3">
                            <PaginationItem>
                                <PaginationLink
                                    first
                                    href={paginator.first_page_url + '&' + params.toString()}
                                    onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(paginator.first_page_url + '&' + params.toString())
                                    }}/>
                            </PaginationItem>
                            {
                                paginator.prev_page_url
                                    ? <PaginationItem>
                                        <PaginationLink
                                            previous
                                            href={paginator.prev_page_url + '&' + params.toString()}
                                            onClick={(e) => {
                                                e.preventDefault();
                                                router.visit(paginator.prev_page_url + '&' + params.toString())
                                            }}/>
                                    </PaginationItem>
                                    : null
                            }
                            {paginator.links.slice(1, paginator.links.length - 1).map((link) => {
                                return (<PaginationItem disabled={!link.url} active={link.active}>
                                    <PaginationLink
                                        className={!link.active ? 'd-none d-sm-none d-md-none' : ''}
                                        href={link.url + '&' + params.toString()}
                                        onClick={(e) => {
                                            e.preventDefault();
                                            router.visit(link.url + '&' + params.toString())
                                        }}
                                    >{link.label}</PaginationLink>
                                </PaginationItem>)
                            })}
                            {
                                paginator.next_page_url
                                    ?
                                    <PaginationItem>
                                        <PaginationLink
                                            next
                                            href={paginator.next_page_url + '&' + params.toString()}
                                            onClick={(e) => {
                                                e.preventDefault();
                                                router.visit(paginator.next_page_url + '&' + params.toString())
                                            }}/>
                                    </PaginationItem>
                                    : null
                            }
                            <PaginationItem>
                                <PaginationLink
                                    last
                                    href={paginator.last_page_url + '&' + params.toString()}
                                    onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(paginator.last_page_url + '&' + params.toString())
                                    }}/>
                            </PaginationItem>
                        </Pagination>
                    </Col>
                </Row>
            </Container>
        </>
    )
}
