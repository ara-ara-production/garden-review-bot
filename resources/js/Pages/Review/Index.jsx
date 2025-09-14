import NavBar from "@/Components/NavBar.jsx";
import Head from "@/Components/Head.jsx";
import {
    Alert,
    Button,
    Col,
    Container,
    DropdownItem,
    DropdownMenu,
    DropdownToggle,
    Form,
    FormFeedback,
    FormGroup, Input,
    Label,
    Pagination,
    PaginationItem,
    PaginationLink,
    Row,
    Table, UncontrolledDropdown
} from "reactstrap";
import {router, useForm, usePage} from "@inertiajs/react";
import React, {useEffect, useState} from "react";

import strBlackDown from '../../../svg/bstr-black-down.svg';
import strBlackUp from '../../../svg/bstr-black-up.svg';
import strGrayDown from '../../../svg/bstr-gray-down.svg';
import strGrayUp from '../../../svg/bstr-gray-up.svg';
import RowFormGroupSelect from "@/Components/RowFormGroupSelect.jsx";

const options = {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
};

export default ({paginator, brunches}) => {

    const {routes} = usePage().props;
    const route = '/' + routes.review_table_prefix + '/' + routes.review

    // Получаем текущий URL
    const queryString = window.location.search;

    const urlParams = new URLSearchParams(queryString);

    const {
        data,
        setData,
        post,
        processing,
        errors,

    } = useForm({
        brunches: [],
        user_id: '',
        two_gis_id: '',
        pupr_user_id: '',
        address: '',
    })

    const [createAnotherOne, setCreateAnotherOne] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        post(`/${routes.backendprefix}/${routes.brunch}` + (createAnotherOne ? `?redirectOnCreation=true` : ''));
    }

    return (<>
        <Head title="Отзывы"/>
        <NavBar/>
        <Container fluid>
            <Row className="my-3">
                <Col>
                    <h2>Отзывы</h2>
                    <Alert color="info">Всего записей: {paginator.total}</Alert>
                    <Form className="row" onSubmit={submit}>
                        <Col
                            className="
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        "
                        >
                            <FormGroup row className="mb-4">
                                <Label sm={3}>Филиал</Label>
                                <Col sm={9}>
                                    


                                    <UncontrolledDropdown>
                                        <DropdownToggle caret>Выбрано: {data.brunches.length}</DropdownToggle>
                                        <DropdownMenu>
                                            {brunches.map((item, i) => (<DropdownItem className='d-flex p-0' color={data.brunches.includes(item.id)} toggle={false} onClick={(e) => e.preventDefault()}>
                                                <Label
                                                    className=""
                                                >
                                                    <span>{item.name}</span>
                                                    <Input onChange={e => {
                                                        setData('brunches', [...data.brunches, e.target.value])
                                                        console.log(data.brunches)
                                                    }} type='checkbox' key={'brunchFilter-' + i} value={item.id}/>
                                                </Label>

                                            </DropdownItem>))}



                                        </DropdownMenu>
                                    </UncontrolledDropdown>
                                    {errors.brunches ? <FormFeedback>{errors.brunches}</FormFeedback> : null}
                                </Col>
                            </FormGroup>
                            <RowFormGroupSelect
                                label="Управляющий"
                                value={data.user_id}
                                onChange={e => setData('user_id', e.target.value)}
                                error={errors.user_id}
                            />
                            <RowFormGroupSelect
                                label="Помошник управляющего"
                                value={data.pupr_user_id}
                                onChange={e => setData('pupr_user_id', e.target.value)}
                                error={errors.pupr_user_id}
                            />
                            <FormGroup>
                                <Button
                                    className="w-100"
                                    type="submit"
                                    value="Сохранить"
                                    color="primary"
                                    outline
                                    disabled={processing}
                                    onClick={() => setCreateAnotherOne(false)}
                                >Фильтровать</Button>
                            </FormGroup>
                        </Col>
                    </Form>
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
                                    <img src={urlParams.get('column') === 'posted_at' && urlParams.get('orderBy') === 'ASC' ? strBlackUp : strGrayUp} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=posted_at&orderBy=ASC')
                                    }}/>
                                    <img src={urlParams.get('column') == 'posted_at' && urlParams.get('orderBy') == 'DESC' ? strBlackDown : strGrayDown} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=posted_at&orderBy=DESC')
                                    }}/>
                                </div>
                            </th>
                            <th className="col-1">
                                <div>Дата начала проверки</div>
                                <div className="d-flex justify-content-center align-items-center gap-2">
                                    <img src={urlParams.get('column') === 'start_work_on' && urlParams.get('orderBy') === 'ASC' ? strBlackUp : strGrayUp} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=start_work_on&orderBy=ASC')
                                    }}/>
                                    <img src={urlParams.get('column') === 'start_work_on' && urlParams.get('orderBy') === 'DESC' ? strBlackDown : strGrayDown} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=start_work_on&orderBy=DESC')
                                    }}/>
                                </div>
                            </th>
                            <th className="col-1">
                                <div>Дата завершения проверки</div>
                                <div className="d-flex justify-content-center align-items-center gap-2">
                                    <img src={urlParams.get('column') === 'end_work_on' && urlParams.get('orderBy') === 'ASC' ? strBlackUp : strGrayUp} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=end_work_on&orderBy=ASC')
                                    }}/>
                                    <img src={urlParams.get('column') === 'end_work_on' && urlParams.get('orderBy') === 'DESC' ? strBlackDown : strGrayDown} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=end_work_on&orderBy=DESC')
                                    }}/>
                                </div>
                            </th>
                            <th>Платформа</th>
                            <th>Филиал</th>
                            <th>
                                <div>Текущий рейтинг</div>
                                <div className="d-flex justify-content-center align-items-center">
                                    <img src={urlParams.get('column') === 'total_brunch_rate' && urlParams.get('orderBy') === 'ASC' ? strBlackUp : strGrayUp} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=total_brunch_rate&orderBy=ASC')
                                    }}/>
                                    <img src={urlParams.get('column') === 'total_brunch_rate' && urlParams.get('orderBy') === 'DESC' ? strBlackDown : strGrayDown} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=total_brunch_rate&orderBy=DESC')
                                    }}/>
                                </div>
                            </th>
                            <th>
                                <div>Оценка</div>
                                <div className="d-flex justify-content-center align-items-center gap-2">

                                    <img src={urlParams.get('column') === 'score' && urlParams.get('orderBy') === 'ASC' ? strBlackUp : strGrayUp } width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=score&orderBy=ASC')
                                    }}/>
                                    <img src={urlParams.get('column') === 'score' && urlParams.get('orderBy') === 'DESC' ? strBlackDown : strGrayDown} width={15} height={15} onClick={(e) => {
                                        e.preventDefault();
                                        router.visit(route + '?column=score&orderBy=DESC')
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
                            <PaginationLink first href={paginator.first_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : '')} onClick={(e) => {
                                e.preventDefault();
                                router.visit(paginator.first_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : ''))
                            }}/>
                        </PaginationItem>
                        {
                            paginator.prev_page_url
                                ? <PaginationItem><PaginationLink previous
                                                                  href={paginator.prev_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : '')}
                                                                  onClick={(e) => {
                                                                      e.preventDefault();
                                                                      router.visit(paginator.prev_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : ''))
                                                                  }}/></PaginationItem>
                                : null
                        }
                        {paginator.links.slice(1, paginator.links.length - 1).map((link) => {
                            return (<PaginationItem disabled={!link.url}
                                                    active={link.active}><PaginationLink
                                href={link.url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : '')} onClick={(e) => {
                                e.preventDefault();
                                router.visit(link.url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : ''))
                            }}>{link.label}</PaginationLink> </PaginationItem>)
                        })}
                        {
                            paginator.next_page_url
                                ?
                                <PaginationItem><PaginationLink next href={paginator.next_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : '')}
                                                                onClick={(e) => {
                                                                    e.preventDefault();
                                                                    router.visit(paginator.next_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : ''))
                                                                }}/></PaginationItem>
                                : null
                        }
                        <PaginationItem>
                            <PaginationLink last href={paginator.last_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : '')} onClick={(e) => {
                                e.preventDefault();
                                router.visit(paginator.last_page_url + (urlParams.get('column') ? ('&column=' + urlParams.get('column')) : '') + (urlParams.get('orderBy') ? ('&orderBy=' + urlParams.get('orderBy')) : ''))
                            }}/>
                        </PaginationItem>

                    </Pagination>
                </Col>
            </Row>
        </Container>
    </>)
}
