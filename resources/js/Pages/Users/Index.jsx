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

export default ({paginator}) => {

    const {routes} = usePage().props;

    return (<>
        <Head title="Пользователи"/>
        <NavBar/>
        <Container fluid>
            <Row className="my-3">
                <Col>
                    <h2>Пользователи</h2>
                    <Alert color="info">Всего записей: {paginator.total}</Alert>
                    <Nav pills>
                        <NavItem>
                            <NavLink
                                href=""
                                onClick={e => {
                                    e.preventDefault();
                                    router.get(  `/${routes.backendprefix}/${routes.user}/create`)
                                }}
                            >
                                Создать <i className="ni ni-fat-add"/>
                            </NavLink>
                        </NavItem>
                    </Nav>
                </Col>
            </Row>
            <Row>
                <Col>
                    <Table striped borderless responsive size="sm" hover>
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Имя</td>
                            <td>Роль</td>
                            <td>tg никнейм</td>
                            <td>Подписан ?</td>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            {paginator.data.map((row, i) => (<tr>
                                <td key={`id-${i}`}>{row.id}</td>
                                <td key={`name-${i}`}>{row.name}</td>
                                <td key={`role-${i}`}>{row.role}</td>
                                <td key={`telegram_username-${i}`}>{row['telegram_username']}</td>
                                <td key={`telegram_chat-${i}`}>{row.is_subscribed
                                    ? <i className="ni ni-check-bold"></i>
                                    : <i className="ni ni-fat-delete"></i>}</td>
                                <td>
                                    <i className="ni ni-fat-remove" onClick={e => {e.preventDefault(); router.delete(`/${routes.backendprefix}/${routes.user}/${row.id}`)}}></i>
                                    <i className="ni ni-settings-gear-65" onClick={e => {e.preventDefault(); router.visit(`/${routes.backendprefix}/${routes.user}/${row.id}/edit`)}}></i>
                                </td>
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
