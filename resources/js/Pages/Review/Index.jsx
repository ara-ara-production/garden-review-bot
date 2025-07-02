import NavBar from "@/Components/NavBar.jsx";
import Head from "@/Components/Head.jsx";
import {
    Alert,
    Col,
    Container,
    Nav,
    NavItem,
    NavLink,
    Pagination,
    PaginationItem,
    PaginationLink,
    Row,
    Table
} from "reactstrap";
import {Link, router, usePage} from "@inertiajs/react";
import React from "react";

const options = {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
};

export default ({paginator}) => {

    const {routes} = usePage().props;

    return (<>
        <Head title="Отзывы"/>
        <NavBar/>
        <Container fluid>
            <Row className="my-3">
                <Col>
                    <h2>ОТзывы</h2>
                    <Alert color="info">Всего записей: {paginator.total}</Alert>
                </Col>
            </Row>
            <Row>
                <Col>
                    <Table striped borderless responsive size="sm" hover>
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Дата публикации отзыва</td>
                            <td>Дата начала проверки</td>
                            <td>Дата завершения проверки</td>
                            <td>Ревью управляющео</td>
                            <td>Рейтинг</td>
                            <td>Платформа</td>
                            <td>Отзыв</td>
                            <td>Филиал</td>
                        </tr>
                        </thead>
                        <tbody>
                            {paginator.data.map((row, i) => (<tr>
                                <td key={`review_id-${i}`}>{row.review_id}</td>
                                <td key={`posted_at-${i}`}>{ new Date(row.posted_at).toLocaleString('ru-RU', options)}</td>
                                <td key={`start_work_on-${i}`}>{row.start_work_on ? new Date(row.start_work_on).toLocaleString('ru-RU', options) : '-'}</td>
                                <td key={`end_work_on-${i}`}>{row.end_work_on ? new Date(row.end_work_on).toLocaleString('ru-RU', options) : '-'}</td>
                                <td key={`control_review-${i}`}>{row.control_review}</td>
                                <td key={`score-${i}`}>{row.score}</td>
                                <td key={`resource-${i}`}>{row.resource}</td>
                                <td key={`comment-${i}`}>{row.comment}</td>
                                <td key={`brunch_name-${i}`}>{row.brunch_name}</td>
                            </tr>))}
                        </tbody>
                    </Table>
                </Col>
            </Row>
            <Row>
                <Col className=" d-flex justify-content-center">
                    <Pagination className=" d-flex justify-content-center gap-3">
                        <PaginationItem>
                            <PaginationLink first href={paginator.first_page_url} onClick={(e) => {
                                e.preventDefault();
                                router.visit(paginator.first_page_url)
                            }}/>
                        </PaginationItem>
                        {
                            paginator.prev_page_url
                                ? <PaginationItem><PaginationLink previous
                                                                  href={paginator.prev_page_url}
                                                                  onClick={(e) => {
                                                                      e.preventDefault();
                                                                      router.visit(paginator.prev_page_url)
                                                                  }}/></PaginationItem>
                                : null
                        }
                        {paginator.links.slice(1, paginator.links.length - 1).map((link) => {
                            return (<PaginationItem disabled={!link.url}
                                                    active={link.active}><PaginationLink
                                href={link.url} onClick={(e) => {
                                e.preventDefault();
                                router.visit(link.url)
                            }}>{link.label}</PaginationLink> </PaginationItem>)
                        })}
                        {
                            paginator.next_page_url
                                ?
                                <PaginationItem><PaginationLink next href={paginator.next_page_url}
                                                                onClick={(e) => {
                                                                    e.preventDefault();
                                                                    router.visit(paginator.next_page_url)
                                                                }}/></PaginationItem>
                                : null
                        }
                        <PaginationItem>
                            <PaginationLink last href={paginator.last_page_url} onClick={(e) => {
                                e.preventDefault();
                                router.visit(paginator.last_page_url)
                            }}/>
                        </PaginationItem>

                    </Pagination>
                </Col>
            </Row>
        </Container>
    </>)
}
