#include <iostream>
#include<fstream>
#include<vector>
using namespace std;
ifstream f("ctc.in");
ofstream g("ctc.out");
int x,y,nr,n,m,i,j,sol=1,st[100000010];
vector<int>v[100010],w[100010],ct[100001];
bool viz[100001],viz1[100001];
inline void s_top(int nod)
{
    viz[nod]=1;
    int sz=v[nod].size();
    if(sz)
    {
        for(int k=0;k<sz;k++)
            if(!viz[v[nod][k]])
            {
                s_top(v[nod][k]);
            }
        st[++nr]=nod;
    }
    else
    {
        st[++nr]=nod;
    }
}
inline void DFS(int nod)
{
    viz1[nod]=1;
    ct[sol].push_back(nod);
    int sz=w[nod].size();
    for(int k=0;k<sz;k++)
        if(!viz1[w[nod][k]])
    {
        DFS(w[nod][k]);
    }
}
int main()
{
    f>>n>>m;
    for(i=1;i<=m;i++)
    {
        f>>x>>y;
        v[x].push_back(y);
        w[y].push_back(x);
    }
    for(i=1;i<=n;i++)
        if(!viz[i])
        s_top(i);
    for(i=nr;i>=1;i--)
    {
        x=st[i];
        if(!viz1[x])
        {
            DFS(x);
            sol++;
        }
    }
    g<<sol-1<<'\n';
    for(i=1;i<=sol;i++)
    {
        for(j=0;j<ct[i].size();j++)
        g<<ct[i][j]<<' ';
        g<<'\n';
    }
}
