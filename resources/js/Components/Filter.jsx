import {Button, Col, Form, FormGroup, Label} from "reactstrap";
import exelIcon from '../../svg/microsoft-excel-svgrepo-com.svg';
import statisticIcon from '../../svg/pie-chart-svgrepo-com.svg'
import Select from 'react-select';
import {CustomProvider, DateRangePicker} from 'rsuite';
import ruRU from 'rsuite/locales/ru_RU';
import qs from "qs";
import {router, usePage} from "@inertiajs/react";
import {useEffect} from "react";

export default ({data, get, setData, processing}) => {
    const {brunches, routes} = usePage().props;

    useEffect(() => {
        if (data.commit) {
            get(location.pathname);
        }
    }, [data]);

    const submit = (e) => {
        e.preventDefault();
        setData('commit', true)
    }

    return <Form className="row m-0" onSubmit={submit}>
        <Col className="border border-primary rounded col-12 mb-4 pt-4">
            <FormGroup row className="mb-4">
                <Label sm={3}>Дата публикации</Label>
                <Col sm={4}>
                    <CustomProvider locale={ruRU}>
                        <div style={{padding: 20}}>
                            <DateRangePicker
                                //date={}
                                value={data.date}
                                onChange={e => setData('date', e)}
                            />
                        </div>
                    </CustomProvider>
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
                                },
                                {
                                    label: 'Яндекс.Еда',
                                    value: 'Яндекс.Еда'
                                },
                                {
                                    label: 'Яндекс.Карты',
                                    value: 'Яндекс.Карты'
                                }
                            ]
                        }
                        isMulti
                        closeMenuOnSelect={false}
                    ></Select>
                </Col>
            </FormGroup>
            <FormGroup row className="mb-4">
                <Col sm={4}>
                    <Button
                        active={data.without_reply}
                        color="info"
                        onClick={
                        e => {
                            e.preventDefault();
                            setData('without_reply', !data.without_reply)
                        }
                    }
                    >Без ответа SMM</Button>
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
                        onClick={(() => {
                            router.visit(`/${routes.review_table_prefix}/${routes.review}/stats?` + qs.stringify(data))
                        })}
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
}
