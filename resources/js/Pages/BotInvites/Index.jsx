import Head from "@/Components/Head.jsx";
import NavBar from "@/Components/NavBar.jsx";
import RowFormGroup from "@/Components/RowFormGroup.jsx";
import RowFormGroupSelect from "@/Components/RowFormGroupSelect.jsx";
import {useForm, usePage} from "@inertiajs/react";
import React from "react";
import {Alert, Button, Col, Container, Form, FormGroup, Row, Table} from "reactstrap";

export default function Index({roles, bots, users, brunches, invites}) {
    const {routes} = usePage().props;
    const {
        data,
        setData,
        post,
        processing,
        errors,
    } = useForm({
        driver: "telegram",
        bot: bots[0]?.name ?? "",
        user_id: "__new__",
        role: roles[0]?.name ?? "NullRole",
        brunch_id: "",
        assignment: "",
        name_hint: "",
        max_uses: 1,
        expires_at: "",
    });

    const assignmentOptions = [
        {name: "", value: "Без назначения в филиал"},
        {name: "user_id", value: "Управляющий"},
        {name: "pupr_user_id", value: "Помощник управляющего"},
    ];

    const botOptions = bots
        .filter((bot) => bot.name.startsWith(data.driver))
        .map((bot) => ({name: bot.name, value: bot.value}));

    const userOptions = [
        {name: "__new__", value: "Создать нового пользователя"},
        ...users,
    ];

    const shouldCreateUser = data.user_id === "__new__";

    const submit = (event) => {
        event.preventDefault();
        post(`/${routes.backendprefix}/${routes.invite}`);
    };

    return (
        <>
            <Head title="Ссылки подписки" />
            <NavBar />
            <Container fluid>
                <Row className="my-3">
                    <Col>
                        <h2>Ссылки быстрой подписки</h2>
                        <Alert color="info">
                            Ссылка привязывает сотрудника к выбранному боту, роли и при необходимости к филиалу.
                        </Alert>
                    </Col>
                </Row>
                <Row>
                    <Col lg="5">
                        <Form onSubmit={submit}>
                            <RowFormGroupSelect
                                label="Платформа"
                                options={[
                                    {name: "telegram", value: "Telegram"},
                                    {name: "vk", value: "VK"},
                                ]}
                                value={data.driver}
                                onChange={(event) => {
                                    const driver = event.target.value;
                                    const nextBot = bots.find((bot) => bot.name.startsWith(driver))?.name ?? "";

                                    setData("driver", driver);
                                    setData("bot", nextBot);
                                }}
                                error={errors.driver}
                            />
                            <RowFormGroupSelect
                                label="Бот"
                                options={botOptions}
                                value={data.bot}
                                onChange={(event) => setData("bot", event.target.value)}
                                error={errors.bot}
                            />
                            <RowFormGroupSelect
                                label="Роль"
                                options={roles}
                                value={data.role}
                                onChange={(event) => setData("role", event.target.value)}
                                error={errors.role}
                            />
                            <RowFormGroupSelect
                                label="Пользователь"
                                options={userOptions}
                                value={data.user_id}
                                onChange={(event) => setData("user_id", event.target.value)}
                                error={errors.user_id}
                            />
                            {shouldCreateUser ? (
                                <RowFormGroup
                                    label="Имя нового пользователя"
                                    inputType="text"
                                    value={data.name_hint}
                                    onChange={(event) => setData("name_hint", event.target.value)}
                                    error={errors.name_hint}
                                />
                            ) : null}
                            <RowFormGroupSelect
                                label="Филиал"
                                options={[{name: "", value: "Без филиала"}, ...brunches]}
                                value={data.brunch_id}
                                onChange={(event) => setData("brunch_id", event.target.value)}
                                error={errors.brunch_id}
                            />
                            <RowFormGroupSelect
                                label="Назначение в филиале"
                                options={assignmentOptions}
                                value={data.assignment}
                                onChange={(event) => setData("assignment", event.target.value)}
                                error={errors.assignment}
                            />
                            <RowFormGroup
                                label="Количество активаций"
                                inputType="number"
                                value={data.max_uses}
                                onChange={(event) => setData("max_uses", event.target.value)}
                                error={errors.max_uses}
                            />
                            <RowFormGroup
                                label="Срок действия"
                                inputType="datetime-local"
                                value={data.expires_at}
                                onChange={(event) => setData("expires_at", event.target.value)}
                                error={errors.expires_at}
                            />
                            <FormGroup>
                                <Button type="submit" color="primary" outline disabled={processing}>
                                    Сгенерировать ссылку
                                </Button>
                            </FormGroup>
                        </Form>
                    </Col>
                    <Col lg="7">
                        <Table striped responsive hover>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Бот</th>
                                    <th>Пользователь</th>
                                    <th>Роль</th>
                                    <th>Филиал</th>
                                    <th>Исп.</th>
                                    <th>Ссылка</th>
                                </tr>
                            </thead>
                            <tbody>
                                {invites.map((invite) => (
                                    <tr key={invite.id}>
                                        <td>{invite.id}</td>
                                        <td>{invite.driver} / {invite.bot}</td>
                                        <td>{invite.user ?? invite.name_hint ?? "Новый"}</td>
                                        <td>{invite.role}</td>
                                        <td>{invite.brunch ?? "—"}</td>
                                        <td>{invite.used_count}/{invite.max_uses}</td>
                                        <td className="text-break">{invite.link}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </Table>
                    </Col>
                </Row>
            </Container>
        </>
    );
}
