import{x,J as h,r as j,j as e}from"./app-lTXa_amd.js";import{H as g}from"./Head-BB09DokN.js";import{N as b}from"./NavBar-DIwVyvHr.js";import{C as _,R as f,a as l,B as v}from"./Alert-CfhHhM0J.js";import{F as w,a as C}from"./Label-BYIQ53rq.js";import{a as n,R as y}from"./RowFormGroupSelect-Cxdb2ocu.js";import{R as N}from"./RowFormGroupWithPrefix-DrXoXz3M.js";import"./Progress-CR1btosj.js";import"./FormFeedback-BQkV2uZ7.js";import"./Input-DCmDpdy8.js";const H=({values:t,roles:m})=>{const{data:a,setData:o,put:p,processing:u,errors:s}=x({name:t.name??"",telegram_username:t.telegram_username??"",vk_user_id:t.vk_user_id??"",email:t.email??"",password:"",password_confirmation:"",role:t.role??"NullRole"}),{routes:i}=h().props,[R,d]=j.useState(!1),c=r=>{r.preventDefault(),p(`/${i.backendprefix}/${i.user}/${t.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(g,{title:"Обновление пользователя"}),e.jsx(b,{}),e.jsxs(_,{children:[e.jsx(f,{children:e.jsx(l,{children:e.jsx("h2",{children:"Обновление пользователя"})})}),e.jsxs(w,{className:"row",onSubmit:c,children:[e.jsxs(l,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(n,{label:"Имя *",inputType:"text",value:a.name,onChange:r=>o("name",r.target.value),error:s.name}),e.jsx(N,{label:"Телеграм никнейм",inputType:"text",value:a.telegram_username,onChange:r=>o("telegram_username",r.target.value),error:s.telegram_username,formText:"Необходимо заполнить, того, чтоб пользователь мог использовать бота"}),e.jsx(n,{label:"VK user id",inputType:"text",value:a.vk_user_id,onChange:r=>o("vk_user_id",r.target.value),error:s.vk_user_id}),e.jsx(y,{label:"Роль",options:m,value:a.role,onChange:r=>o("role",r.target.value),error:s.role})]}),e.jsx("h6",{className:"pl-0 text-muted",children:"При заполнении пользователь сможет входить в админ панель:"}),e.jsxs(l,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(n,{label:"Почта",inputType:"email",value:a.email,onChange:r=>o("email",r.target.value),error:s.email}),e.jsx(n,{label:"Пароль",inputType:"password",value:a.password,onChange:r=>o("password",r.target.value),error:s.password}),e.jsx(n,{label:"Повтор пароля",inputType:"password",value:a.password_confirmation,onChange:r=>o("password_confirmation",r.target.value),error:s.password_confirmation})]}),e.jsx(l,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(C,{children:e.jsx(v,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:u,onClick:()=>d(!1),children:"Обновить"})})})]})]})]})};export{H as default};
