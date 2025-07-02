import Head from "@/Components/Head.jsx";
import React, {useState} from "react";
import NavBar from "@/Components/NavBar.jsx";
import {Button, Col, Container, Form, Row, FormGroup, Alert} from "reactstrap";
import {useForm, usePage} from "@inertiajs/react";
import RowFormGroup from "@/Components/RowFormGroup.jsx";
import RowFormGroupWithPrefix from "@/Components/RowFormGroupWithPrefix.jsx";
import SubmitButton from "@/Components/SubmitButton.jsx";
import RowFormGroupSelect from "@/Components/RowFormGroupSelect.jsx";

export default ({roles}) => {

    const {
        data,
        setData,
        post,
        processing,
        errors,
        reset

    } = useForm({
        name: '',
        telegram_username: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'NullRole'
    })

    const {routes} = usePage().props;
    const [createAnotherOne, setCreateAnotherOne] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        post(`/${routes.backendprefix}/${routes.user}` + (createAnotherOne ? `?redirectOnCreation=true` : ''));
        createAnotherOne ? reset() : null;
    }
    return (<>
        <Head title="Создание пользователя"/>
        <NavBar/>
        <Container>
            <Row><Col><h2>Cоздание пользователя</h2></Col></Row>
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
                    <RowFormGroup
                        label="Имя *"
                        inputType="text"
                        value={data.name}
                        onChange={e => setData('name', e.target.value)}
                        error={errors.name}
                    />
                    <RowFormGroupWithPrefix
                        label="Телеграм никнейм"
                        inputType="text"
                        value={data.telegram_username}
                        onChange={e => setData('telegram_username', e.target.value)}
                        error={errors.telegram_username}
                        formText="Необходимо заполнить, того, чтоб пользователь мог использовать бота"
                    />
                    <RowFormGroupSelect
                        label="Роль"
                        options={roles}
                        value={data.role}
                        onChange={e => setData('role', e.target.value)}
                        error={errors.role}
                    />
                </Col>

                <h6 className="pl-0 text-muted">При заполнении пользователь сможет входить в админ панель:</h6>
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
                    <RowFormGroup
                        label="Почта"
                        inputType="email"
                        value={data.email}
                        onChange={e => setData('email', e.target.value)}
                        error={errors.email}
                    />
                    <RowFormGroup
                        label="Пароль"
                        inputType="password"
                        value={data.password}
                        onChange={e => setData('password', e.target.value)}
                        error={errors.password}
                    />
                    <RowFormGroup
                        label="Повтор пароля"
                        inputType="password"
                        value={data.password_confirmation}
                        onChange={e => setData('password_confirmation', e.target.value)}
                        error={errors.password_confirmation}
                    />
                </Col>
                <Col
                    className="
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        "
                >
                    <FormGroup>
                        <Button
                            className="w-100"
                            type="submit"
                            value="Сохранить"
                            color="primary"
                            outline
                            disabled={processing}
                            onClick={() => setCreateAnotherOne(false)}
                        >Сохранить</Button>
                    </FormGroup>
                </Col>
                <Col
                    className="
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        "
                >
                    <FormGroup>
                        <Button
                            className="w-100"
                            type="submit"
                            value="Сохранить и создать еще"
                            color="primary"
                            outline
                            disabled={processing}
                            onClick={() => setCreateAnotherOne(true)}
                        >Сохранить и создать еще</Button>
                    </FormGroup>
                </Col>
            </Form>
        </Container>
    </>);
}
