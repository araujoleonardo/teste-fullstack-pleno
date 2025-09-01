-- Usuários com mais produtos
create view usuario_mais_produto as
select
  use.id,
  use.name,
  count(pro.id) as total_produto
  from users use
    left join products pro on use.id = pro.user_id
    group by use.id, use.name
    order by total_produto desc;

-- Produto mais caro por usuário
create view produto_mais_caro as
select
  distinct on (use.id)
    use.id as user_id,
    use.name as user_name,
    pro.id as prod_id,
    pro.name as prod_name,
    pro.price
    from users use
      left join products pro on use.id = pro.user_id
      order by use.id, pro.price desc;

-- Quantidade de produtos por faixa de preço
-- 0 a 100
-- 101 a 500
-- 501 a 1000
-- > 1000
create view produto_faixa_preco as
select case
      when price between 0 and 100 then '0 a 100'
      when price between 101 and 500 then '101 a 500'
      when price between 501 and 1000 then '501 a 1000'
      else '> 1000'
    end as faixa_preco,
    count(*) as total_produto
  from products
    group by faixa_preco
    order by faixa_preco;
