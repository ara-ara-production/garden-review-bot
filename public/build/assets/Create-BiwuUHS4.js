import{u as b,a as g,r as f,j as e}from"./app-CZUs4bFv.js";import{H as w}from"./Head-DCKL1LJ9.js";import{N as v}from"./NavBar-D6hSJFqI.js";import{C,R as y,a as n,B as u}from"./Alert-oS2A1CcT.js";import{F as N,a as c}from"./Label-B311HVj_.js";import{R as t,a as R}from"./RowFormGroupSelect-DT2VQD0Y.js";import{R as F}from"./RowFormGroupWithPrefix-B37w7EHq.js";import"./Progress-BxdBp0H_.js";import"./Input-D0y16emI.js";const E=({roles:d})=>{const{data:a,setData:s,post:x,processing:l,errors:o,reset:h}=b({name:"",telegram_username:"",email:"",password:"",password_confirmation:"",role:"NullRole"}),{routes:m}=g().props,[i,p]=f.useState(!1),j=r=>{r.preventDefault(),x(`/${m.backendprefix}/${m.user}`+(i?"?redirectOnCreation=true":"")),i&&h()};return e.jsxs(e.Fragment,{children:[e.jsx(w,{title:"Создание пользователя"}),e.jsx(v,{}),e.jsxs(C,{children:[e.jsx(y,{children:e.jsx(n,{children:e.jsx("h2",{children:"Cоздание пользователя"})})}),e.jsxs(N,{className:"row",onSubmit:j,children:[e.jsxs(n,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(t,{label:"Имя *",inputType:"text",value:a.name,onChange:r=>s("name",r.target.value),error:o.name}),e.jsx(F,{label:"Телеграм никнейм",inputType:"text",value:a.telegram_username,onChange:r=>s("telegram_username",r.target.value),error:o.telegram_username,formText:"Необходимо заполнить, того, чтоб пользователь мог использовать бота"}),e.jsx(R,{label:"Роль",options:d,value:a.role,onChange:r=>s("role",r.target.value),error:o.role})]}),e.jsx("h6",{className:"pl-0 text-muted",children:"При заполнении пользователь сможет входить в админ панель:"}),e.jsxs(n,{className:`
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
                        `,children:e.jsx(c,{children:e.jsx(u,{className:"w-100",type:"submit",value:"Сохранить",color:"primary",outline:!0,disabled:l,onClick:()=>p(!1),children:"Сохранить"})})}),e.jsx(n,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(c,{children:e.jsx(u,{className:"w-100",type:"submit",value:"Сохранить и создать еще",color:"primary",outline:!0,disabled:l,onClick:()=>p(!0),children:"Сохранить и создать еще"})})})]})]})]})};export{E as default};
