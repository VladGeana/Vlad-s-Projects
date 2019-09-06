#include <iostream>
#include<fstream>
#include<vector>
#include<utility>
#define Nmax 1000001
using namespace std;
ifstream f("dijkstra.in");
ofstream g("dijkstra.out");
struct nod_cost
{
    int nod,cost;
};
nod_cost h[50001];
vector<pair<int,int> >v[50001];
int n1,m,cst,x,y,n,i,j,k,heap_node[50001],dist[50001];
//int parrent[50001];
inline int father(int nod)
{
    return nod>>1;
}
inline int left_son(int nod)
{
    return nod<<1;
}
inline int right_son(int nod)
{
    return (nod<<1)+1;
}
void up(int p)
{
    while(p>1 && h[p].cost<h[p/2].cost)
    {
        swap(heap_node[h[p].nod],heap_node[h[p/2].nod]);
        swap(h[p],h[p/2]);
        p/=2;
    }
}

void down(int p)
{
    if((p<<1)<=n && (p<<1)+1<=n && h[p<<1].cost<h[(p<<1)+1].cost && h[p<<1].cost<h[p].cost)
    {
        swap(heap_node[h[p].nod],heap_node[h[p<<1].nod]);
        swap(h[p],h[p<<1]);
        down(p<<1);
    }
    else
    if((p<<1)<=n && (p<<1)+1<=n && h[p<<1].cost>h[(p<<1)+1].cost && h[(p<<1)+1].cost<h[p].cost)
    {
        swap(heap_node[h[p].nod],heap_node[h[(p<<1)+1].nod]);
        swap(h[p],h[(p<<1)+1]);
        down((p<<1)+1);
    }
    else
    if((p<<1)<=n && h[p<<1].cost<h[p].cost)
    {
        swap(heap_node[h[p].nod],heap_node[h[p<<1].nod]);
        swap(h[p],h[p<<1]);
        down(p<<1);
    }
}

inline void cut(int k)
{
    swap(heap_node[h[k].nod],heap_node[h[n].nod]);
    swap(h[k],h[n]);
    n--;
    up(k);
    down(k);
}
inline int extractMin()
{
    return h[1].nod;
}
inline void BuildHeap()
{
    for(int i=2;i<=n;i++)
        up(i);
}
int main()
{
    f>>n>>m;
    n1=n;
    for(i=1;i<=m;i++)
    {
        f>>x>>y>>cst;
        v[x].push_back(make_pair(y,cst));
    }
    for(i=1;i<=n;i++)
    {
        h[i].nod=i;
        heap_node[i]=i;
        h[i].cost=Nmax;
    }
    h[1].cost=0;
    BuildHeap();
    dist[1]=0;
//    parrent[1]=0;
    while(n>0)
    {
        i=extractMin();

        for(k=0;k<v[i].size();k++)
            if(heap_node[v[i][k].first]<=n)
        {
            j=heap_node[v[i][k].first];
            if(h[j].cost>h[heap_node[i]].cost+v[i][k].second)
            {
                h[j].cost=h[heap_node[i]].cost+v[i][k].second;
                //parrent[v[i][k].first]=i;
                dist[v[i][k].first]=h[j].cost;
                up(j);
                down(j);
            }
        }
        cut(heap_node[i]);
    }
    for(i=2;i<=n1;i++)
        g<<dist[i]<<' ';
}
