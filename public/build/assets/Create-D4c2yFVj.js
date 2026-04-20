import{x as b,J as v,r as g,j as e}from"./app-lTXa_amd.js";import{H as f}from"./Head-BB09DokN.js";import{N as w}from"./NavBar-DIwVyvHr.js";import{C,R as _,a as n,B as u}from"./Alert-CfhHhM0J.js";import{F as y,a as d}from"./Label-BYIQ53rq.js";import{a as t,R as N}from"./RowFormGroupSelect-Cxdb2ocu.js";import{R}from"./RowFormGroupWithPrefix-DrXoXz3M.js";import"./Progress-CR1btosj.js";import"./FormFeedback-BQkV2uZ7.js";import"./Input-DCmDpdy8.js";const H=({roles:c})=>{const{data:a,setData:s,post:x,processing:l,errors:o,reset:h}=b({name:"",telegram_username:"",vk_user_id:"",email:"",password:"",password_confirmation:"",role:"NullRole"}),{routes:i}=v().props,[m,p]=g.useState(!1),j=r=>{r.preventDefault(),x(`/${i.backendprefix}/${i.user}`+(m?"?redirectOnCreation=true":"")),m&&h()};return e.jsxs(e.Fragment,{children:[e.jsx(f,{title:"Создание пользователя"}),e.jsx(w,{}),e.jsxs(C,{children:[e.jsx(_,{children:e.jsx(n,{children:e.jsx("h2",{children:"Cоздание пользователя"})})}),e.jsxs(y,{className:"row",onSubmit:j,children:[e.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"Имя *",inputType:"text",value:a.name,onChange:r=>s("name",r.target.value),error:o.name}),e.jsx(R,{label:"Телеграм никнейм",inputType:"text",value:a.telegram_username,onChange:r=>s("telegram_username",r.target.value),error:o.telegram_username,formText:"Необходимо заполнить, того, чтоб пользователь мог использовать бота"}),e.jsx(t,{label:"VK user id",inputType:"text",value:a.vk_user_id,onChange:r=>s("vk_user_id",r.target.value),error:o.vk_user_id}),e.jsx(N,{label:"Роль",options:c,value:a.role,onChange:r=>s("role",r.target.value),error:o.role})]}),e.jsx("h6",{className:"pl-0 text-muted",children:"При заполнении пользователь сможет входить в админ панель:"}),e.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"Почта",inputType:"email",value:a.email,onChange:r=>s("email",r.target.value),error:o.email}),e.jsx(t,{label:"Пароль",inputType:"password",value:a.password,onChange:r=>s("password",r.target.value),error:o.password}),e.jsx(t,{label:"Повтор пароля",inputType:"password",value:a.password_confirmation,onChange:r=>s("password_confirmation",r.target.value),error:o.password_confirmation})]}),e.jsx(n,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(d,{children:e.jsx(u,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:l,onClick:()=>p(!1),children:"Сохранить"})})}),e.jsx(n,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(d,{children:e.jsx(u,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:l,onClick:()=>p(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{H as default};
