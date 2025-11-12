import NavBar from "@/Components/NavBar.jsx";
import Head from "@/Components/Head.jsx";
import {
    Alert, Badge, Button,
    Col,
    Container, Modal, ModalBody, ModalFooter, ModalHeader,
    Pagination,
    PaginationItem,
    PaginationLink,
    Row,
    Table, UncontrolledCollapse, UncontrolledTooltip
} from "reactstrap";
import {router, useForm, usePage} from "@inertiajs/react";
import React, {useState} from "react";
import strBlackDown from '../../../svg/bstr-black-down.svg';
import strBlackUp from '../../../svg/bstr-black-up.svg';
import strGrayDown from '../../../svg/bstr-gray-down.svg';
import strGrayUp from '../../../svg/bstr-gray-up.svg';
import Filter from "@/Components/Filter.jsx";

const options = {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
};

export default ({paginator}) => {
    const [selectedReview, setSelectedReview] = useState(null);
    const [modal, setModal] = useState(false);
    const toggle = () => setModal(!modal);

    const {brunches, filtersAndSort, routes} = usePage().props;
    const route = '/' + routes.review_table_prefix + '/' + routes.review

    const params = new URLSearchParams(window.location.search);
    params.delete('page');

    console.log(filtersAndSort)

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
        without_reply: filtersAndSort.without_reply ? filtersAndSort.without_reply === 'true' : false,
    })

    return (<>
            {!selectedReview ? null :
                <Modal isOpen={modal} toggle={toggle} size="lg">
                    <ModalHeader toggle={toggle}><span># {selectedReview.review_id}</span>
                        <span style={{display: 'flex', gap: "15px"}}>
                            <Badge className="text-dark pl-2 w-auto"
                                   color="light"
                            >{selectedReview.resource.toUpperCase()}</Badge>
                            <Badge className="text-dark pl-2 w-auto"
                                   color="light"
                            >{selectedReview.brunch_name}</Badge>
                            <Badge className="text-dark pl-2 w-auto"
                                   color="light"
                            >{selectedReview.total_brunch_rate}</Badge>
                            <Badge className="pl-2 w-auto"
                                   color={selectedReview.score <= 3 ? "danger" : (selectedReview.score == 4 ? "warning" : "success")}>{selectedReview.score}</Badge>
                            <Badge className="text-dark w-auto"
                                   color="light"
                            >{selectedReview.sender}</Badge>
                        </span>
                    </ModalHeader>
                    <ModalBody>
                        <Row className="mb-2">
                            <Col sm={4} className="pl-2 pr-2 d-flex justify-content-center align-items-center">
                                <UncontrolledTooltip
                                    placement="top"
                                    target="publish_date"
                                >
                                    Дата публикации отзыва
                                </UncontrolledTooltip>
                                <Button id="publish_date" onClick={e => e.preventDefault()} color="info" size="sm"
                                        outline>
                                    {new Date(selectedReview.posted_at.replace(/Z$/, '')).toLocaleString('ru-RU', options)}
                                </Button>
                            </Col>
                            <Col sm={4} className="pl-2 pr-2 d-flex justify-content-center align-items-center">
                                <UncontrolledTooltip
                                    placement="top"
                                    target="check_date"
                                >
                                    Дата начала проверки
                                </UncontrolledTooltip>
                                <Button id="check_date" onClick={e => e.preventDefault()} color="info" size="sm"
                                        outline>
                                    {selectedReview.start_work_on ? new Date(selectedReview.start_work_on).toLocaleString('ru-RU', options) : '-'}
                                </Button>
                            </Col>
                            <Col sm={4} className="pl-2 pr-2 d-flex justify-content-center align-items-center">
                                <UncontrolledTooltip
                                    placement="top"
                                    target="end_check_date"
                                >
                                    Дата завершении проверки
                                </UncontrolledTooltip>
                                <Button id="end_check_date" onClick={e => e.preventDefault()} color="info" size="sm"
                                        outline>
                                    {selectedReview.end_work_on ? new Date(selectedReview.end_work_on).toLocaleString('ru-RU', options) : '-'}
                                </Button>
                            </Col>
                        </Row>
                        <Row className="mb-2">

                        </Row>
                        <Row className="mb-2">
                            <Col className="text-left text-muted" sm={12}>
                                Отзыв
                            </Col>
                            <Col
                                className="border-primary rounded border p-3"
                                sm={12}
                            >
                                {selectedReview.comment}
                            </Col>
                        </Row>
                        <Row className="mb-2">
                            <Col className="text-left text-muted" sm={12}>
                                Комментарий управляющего
                            </Col>
                            <Col
                                className="border-primary rounded border p-3"
                                sm={12}
                            >
                                {selectedReview.control_review}
                            </Col>
                        </Row>
                        <Row>
                            <Col className="text-left text-muted" sm={12}>
                                Ответ SMM на платформе
                            </Col>
                            <Col
                                className="border-primary rounded border p-3"
                                sm={12}
                            >
                                {selectedReview.final_answer}
                            </Col>
                        </Row>
                    </ModalBody>
                </Modal>
            }


            <Head title="Отзывы"/>
            <NavBar/>
            <Container fluid>
                <Row className="my-3">
                    <Col>
                        <h2>Отзывы</h2>
                        <Alert color="info">Всего записей: {paginator.total}</Alert>
                        <Container fluid className="p-0">
                            <Col xl={8}>
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
                                <th className="text-center">Отправитель</th>
                                <th className="col-3">Отзыв</th>
                                <th className="col-3">Комментарий управляющего</th>
                                <th className="col-3">Ответ SMM на платформе</th>
                            </tr>
                            </thead>
                            <tbody>
                            {paginator.data.map((row, i) => (<tr onClick={() => {
                                setSelectedReview(row);
                                toggle()
                            }}>
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
                                <td className="text-center" key={`sender-${i}`}>{row.sender}</td>
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
